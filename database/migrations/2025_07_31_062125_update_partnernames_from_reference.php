<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update entries with empty partnername to use reference
        $entries = DB::table('bankdata')
            ->whereNull('partnername')
            ->whereNotNull('reference')
            ->get();

        foreach ($entries as $entry) {
            $newPartnername = $entry->reference;
            
            // Kürze den Partner-Namen wenn er zu lang ist
            if (strlen($newPartnername) > 100) {
                $newPartnername = substr($newPartnername, 0, 97) . '...';
            }
            
            DB::table('bankdata')
                ->where('id', $entry->id)
                ->update(['partnername' => $newPartnername]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this migration safely
    }
};
