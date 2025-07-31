<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBankDataTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bankdata:update-types {--force : Force update even if types are already set}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update bank data types based on amount (positive = income, negative = expense)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting bank data type update...');

        // Zähle Datensätze vor der Aktualisierung
        $totalRecords = DB::table('bankdata')->count();
        $recordsWithoutType = DB::table('bankdata')
            ->whereNull('type')
            ->orWhere('type', '')
            ->count();

        $this->info("Total records: {$totalRecords}");
        $this->info("Records without type: {$recordsWithoutType}");

        if ($recordsWithoutType === 0 && !$this->option('force')) {
            $this->warn('All records already have types set. Use --force to update anyway.');
            return;
        }

        // Aktualisiere alle Bankdaten basierend auf dem Betrag
        $updatedCount = DB::table('bankdata')
            ->when(!$this->option('force'), function ($query) {
                return $query->whereNull('type')->orWhere('type', '');
            })
            ->update([
                'type' => DB::raw('CASE WHEN amount >= 0 THEN "income" ELSE "expense" END')
            ]);

        // Zähle die Ergebnisse
        $incomeCount = DB::table('bankdata')->where('type', 'income')->count();
        $expenseCount = DB::table('bankdata')->where('type', 'expense')->count();

        $this->info("Updated {$updatedCount} records");
        $this->info("Income records: {$incomeCount}");
        $this->info("Expense records: {$expenseCount}");

        // Log der Änderungen
        Log::info("Bankdaten-Typen aktualisiert via Command", [
            'updated_records' => $updatedCount,
            'total_records' => $totalRecords,
            'income_count' => $incomeCount,
            'expense_count' => $expenseCount
        ]);

        $this->info('Bank data types updated successfully!');
    }
}
