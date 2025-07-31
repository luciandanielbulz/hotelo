<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class CheckCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all categories and their types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking categories...');
        
        $categories = Category::all(['id', 'name', 'type', 'client_id']);
        
        $this->table(
            ['ID', 'Name', 'Type', 'Client ID'],
            $categories->map(function($cat) {
                return [
                    $cat->id,
                    $cat->name,
                    $cat->type ?? 'NULL',
                    $cat->client_id
                ];
            })
        );
        
        $incomeCount = $categories->where('type', 'income')->count();
        $expenseCount = $categories->where('type', 'expense')->count();
        $nullCount = $categories->whereNull('type')->count();
        
        $this->info("Income categories: {$incomeCount}");
        $this->info("Expense categories: {$expenseCount}");
        $this->info("Categories with NULL type: {$nullCount}");
        
        return 0;
    }
} 