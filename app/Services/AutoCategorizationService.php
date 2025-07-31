<?php

namespace App\Services;

use App\Models\BankData;
use App\Models\Category;
use Illuminate\Support\Facades\Log;

class AutoCategorizationService
{
    /**
     * Automatically categorize a single transaction
     */
    public function categorizeTransaction(BankData $transaction, $respectType = true)
    {
        $clientId = $transaction->client_id;
        $categories = Category::active()->forClient($clientId);
        
        // Optional: Nur Kategorien mit dem gleichen Typ berücksichtigen
        if ($respectType) {
            $categories = $categories->where('type', $transaction->type);
        }
        
        $categories = $categories->get();
        
        $bestMatch = null;
        $bestScore = 0;

        foreach ($categories as $category) {
            $score = $category->getMatchScore($transaction);
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMatch = $category;
            }
        }

        if ($bestMatch && $bestScore > 0) {
            // Nur die Kategorie zuweisen, den Typ nicht verändern
            $transaction->category_id = $bestMatch->id;
            $transaction->save();

            Log::info('Transaction auto-categorized', [
                'transaction_id' => $transaction->id,
                'category_id' => $bestMatch->id,
                'category_name' => $bestMatch->name,
                'score' => $bestScore,
                'partnername' => $transaction->partnername,
                'reference' => $transaction->reference,
                'original_type' => $transaction->type,
                'category_type' => $bestMatch->type
            ]);

            return $bestMatch;
        }

        return null;
    }

    /**
     * Automatically categorize multiple transactions
     */
    public function categorizeTransactions($transactions = null, $clientId = null)
    {
        if ($transactions === null) {
            $query = BankData::whereNull('category_id');
            if ($clientId) {
                $query->where('client_id', $clientId);
            }
            $transactions = $query->get();
        }

        $results = [
            'total' => $transactions->count(),
            'categorized' => 0,
            'failed' => 0,
            'categories_used' => []
        ];

        foreach ($transactions as $transaction) {
            $category = $this->categorizeTransaction($transaction);
            
            if ($category) {
                $results['categorized']++;
                $categoryName = $category->name;
                if (!isset($results['categories_used'][$categoryName])) {
                    $results['categories_used'][$categoryName] = 0;
                }
                $results['categories_used'][$categoryName]++;
            } else {
                $results['failed']++;
            }
        }

        Log::info('Auto-categorization completed', $results);

        return $results;
    }

    /**
     * Get suggestions for keywords based on uncategorized transactions
     */
    public function getKeywordSuggestions($clientId = null)
    {
        $query = BankData::whereNull('category_id');
        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        $transactions = $query->get();
        $suggestions = [];

        foreach ($transactions as $transaction) {
            $partnername = trim($transaction->partnername);
            $reference = trim($transaction->reference);
            
            if (!empty($partnername)) {
                $words = preg_split('/\s+/', $partnername);
                foreach ($words as $word) {
                    $word = trim(strtolower($word));
                    if (strlen($word) > 2 && !in_array($word, ['gmbh', 'ag', 'kg', 'ohg', 'e.v.', 'e.v', 'co', 'ltd', 'inc'])) {
                        if (!isset($suggestions[$word])) {
                            $suggestions[$word] = 0;
                        }
                        $suggestions[$word]++;
                    }
                }
            }

            if (!empty($reference)) {
                $words = preg_split('/\s+/', $reference);
                foreach ($words as $word) {
                    $word = trim(strtolower($word));
                    if (strlen($word) > 2 && !in_array($word, ['gmbh', 'ag', 'kg', 'ohg', 'e.v.', 'e.v', 'co', 'ltd', 'inc'])) {
                        if (!isset($suggestions[$word])) {
                            $suggestions[$word] = 0;
                        }
                        $suggestions[$word]++;
                    }
                }
            }
        }

        arsort($suggestions);
        return array_slice($suggestions, 0, 20, true); // Top 20 suggestions
    }

    /**
     * Test categorization for a specific transaction
     */
    public function testCategorization(BankData $transaction, $respectType = true)
    {
        $clientId = $transaction->client_id;
        $categories = Category::active()->forClient($clientId);
        
        // Optional: Nur Kategorien mit dem gleichen Typ berücksichtigen
        if ($respectType) {
            $categories = $categories->where('type', $transaction->type);
        }
        
        $categories = $categories->get();
        
        $matches = [];

        foreach ($categories as $category) {
            $score = $category->getMatchScore($transaction);
            if ($score > 0) {
                $matches[] = [
                    'category' => $category,
                    'score' => $score,
                    'keywords' => $category->getKeywordsArrayAttribute()
                ];
            }
        }

        // Sort by score descending
        usort($matches, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return $matches;
    }
} 