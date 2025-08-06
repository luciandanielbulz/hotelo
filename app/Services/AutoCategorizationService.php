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
            
            // Ganze Phrasen aus Partnername extrahieren
            if (!empty($partnername)) {
                // Entferne häufige Firmenendungen und bereinige
                $cleanPartnername = preg_replace('/\b(gmbh|ag|kg|ohg|e\.v\.|e\.v|co|ltd|inc)\b/i', '', $partnername);
                $cleanPartnername = trim($cleanPartnername);
                
                if (strlen($cleanPartnername) > 3) {
                    if (!isset($suggestions[$cleanPartnername])) {
                        $suggestions[$cleanPartnername] = 0;
                    }
                    $suggestions[$cleanPartnername]++;
                }
            }

            // Ganze Phrasen aus Referenz extrahieren
            if (!empty($reference)) {
                $cleanReference = trim($reference);
                if (strlen($cleanReference) > 3) {
                    if (!isset($suggestions[$cleanReference])) {
                        $suggestions[$cleanReference] = 0;
                    }
                    $suggestions[$cleanReference]++;
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

    /**
     * Add keyword to category
     */
    public function addKeywordToCategory($categoryId, $keyword, $clientId = null)
    {
        $category = Category::find($categoryId);
        
        if (!$category) {
            return ['success' => false, 'message' => 'Kategorie nicht gefunden'];
        }

        // Prüfe ob die Kategorie zum richtigen Client gehört
        if ($clientId && $category->client_id != $clientId) {
            return ['success' => false, 'message' => 'Keine Berechtigung für diese Kategorie'];
        }

        // Hole aktuelle Keywords
        $currentKeywords = $category->keywords ?: '';
        $keywordsArray = array_filter(array_map('trim', explode(',', $currentKeywords)));
        
        // Füge neues Keyword hinzu, falls es noch nicht existiert
        if (!in_array($keyword, $keywordsArray)) {
            $keywordsArray[] = $keyword;
            $category->keywords = implode(', ', $keywordsArray);
            $category->save();

            return ['success' => true, 'message' => "Keyword '$keyword' wurde zur Kategorie '$category->name' hinzugefügt"];
        } else {
            return ['success' => false, 'message' => "Keyword '$keyword' existiert bereits in der Kategorie '$category->name'"];
        }
    }
} 