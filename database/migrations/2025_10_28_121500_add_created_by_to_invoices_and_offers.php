<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up(): void
	{
		Schema::table('invoices', function (Blueprint $table) {
			if (!Schema::hasColumn('invoices', 'created_by')) {
				$table->foreignId('created_by')->nullable()->after('comment')->constrained('users')->nullOnDelete();
			}
			if (!Schema::hasColumn('invoices', 'client_version_id')) {
				$table->foreignId('client_version_id')->nullable()->after('customer_id')->constrained('clients')->nullOnDelete();
			}
		});

		Schema::table('offers', function (Blueprint $table) {
			if (!Schema::hasColumn('offers', 'created_by')) {
				$table->foreignId('created_by')->nullable()->after('comment')->constrained('users')->nullOnDelete();
			}
			if (!Schema::hasColumn('offers', 'client_version_id')) {
				$table->foreignId('client_version_id')->nullable()->after('customer_id')->constrained('clients')->nullOnDelete();
			}
		});
	}

	public function down(): void
	{
		Schema::table('invoices', function (Blueprint $table) {
			if (Schema::hasColumn('invoices', 'created_by')) {
				$table->dropConstrainedForeignId('created_by');
			}
			if (Schema::hasColumn('invoices', 'client_version_id')) {
				$table->dropConstrainedForeignId('client_version_id');
			}
		});

		Schema::table('offers', function (Blueprint $table) {
			if (Schema::hasColumn('offers', 'created_by')) {
				$table->dropConstrainedForeignId('created_by');
			}
			if (Schema::hasColumn('offers', 'client_version_id')) {
				$table->dropConstrainedForeignId('client_version_id');
			}
		});
	}
};


