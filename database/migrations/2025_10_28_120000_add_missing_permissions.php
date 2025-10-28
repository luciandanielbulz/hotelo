<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		$now = now();
		$permissions = [
			// Wird zum Entsperren/Herabstufen bezahlter Rechnungen verwendet
			'unlock_invoices' => [
				'description' => 'Bezahlte Rechnungen entsperren bzw. Status herabstufen',
				'category' => 'Rechnungen',
			],
			// Wird für Postausgang/Listenansicht gesendeter E-Mails verwendet
			'view_email_list' => [
				'description' => 'Postausgang (gesendete E-Mails) ansehen',
				'category' => 'Kommunikation',
			],
		];

		foreach ($permissions as $name => $meta) {
			$exists = DB::table('permissions')->where('name', $name)->exists();
			if (!$exists) {
				$data = [
					'name' => $name,
					'description' => $meta['description'] ?? null,
					'created_at' => $now,
					'updated_at' => $now,
				];
				// Optionales Feld nur setzen, wenn Spalte existiert
				if (Schema::hasColumn('permissions', 'category')) {
					$data['category'] = $meta['category'] ?? null;
				}
				DB::table('permissions')->insert($data);
			}
		}
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		DB::table('permissions')->whereIn('name', [
			'unlock_invoices',
			'view_email_list',
		])->delete();
	}
};


