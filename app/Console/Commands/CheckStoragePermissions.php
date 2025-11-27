<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckStoragePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prüft Storage-Verzeichnisse und Berechtigungen';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Prüfe Storage-Struktur und Berechtigungen...');
        $this->newLine();

        $issues = [];
        $success = [];

        // Prüfe storage/app/public
        $publicPath = storage_path('app/public');
        $this->checkDirectory($publicPath, 'storage/app/public', $issues, $success);

        // Prüfe storage/app/public/logos
        $logosPath = storage_path('app/public/logos');
        $this->checkDirectory($logosPath, 'storage/app/public/logos', $issues, $success);

        // Prüfe public/storage Link
        $storageLink = public_path('storage');
        if (is_link($storageLink)) {
            $target = readlink($storageLink);
            if ($target === storage_path('app/public')) {
                $success[] = 'Storage-Link existiert und zeigt auf korrektes Verzeichnis';
            } else {
                $issues[] = "Storage-Link zeigt auf falsches Verzeichnis: {$target}";
            }
        } elseif (file_exists($storageLink)) {
            $issues[] = 'public/storage existiert, ist aber kein Link';
        } else {
            $issues[] = 'Storage-Link existiert nicht. Führen Sie "php artisan storage:link" aus.';
        }

        // Prüfe public/temp_logos
        $tempLogosPath = public_path('temp_logos');
        $this->checkDirectory($tempLogosPath, 'public/temp_logos', $issues, $success, false);

        // Zeige Ergebnisse
        $this->newLine();
        if (!empty($success)) {
            $this->info('✓ Erfolgreiche Prüfungen:');
            foreach ($success as $msg) {
                $this->line('  ' . $msg);
            }
            $this->newLine();
        }

        if (!empty($issues)) {
            $this->error('✗ Gefundene Probleme:');
            foreach ($issues as $issue) {
                $this->line('  ' . $issue);
            }
            $this->newLine();
            $this->warn('Bitte beheben Sie diese Probleme, bevor Sie Logos hochladen.');
            return 1;
        } else {
            $this->info('✓ Alle Prüfungen erfolgreich! Storage ist korrekt konfiguriert.');
            return 0;
        }
    }

    private function checkDirectory($path, $name, &$issues, &$success, $mustExist = true)
    {
        if (!file_exists($path)) {
            if ($mustExist) {
                $issues[] = "Verzeichnis {$name} existiert nicht";
            }
            return;
        }

        if (!is_dir($path)) {
            $issues[] = "{$name} existiert, ist aber kein Verzeichnis";
            return;
        }

        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $readable = is_readable($path);
        $writable = is_writable($path);

        if (!$readable) {
            $issues[] = "Verzeichnis {$name} ist nicht lesbar (Berechtigungen: {$perms})";
        } elseif (!$writable) {
            $issues[] = "Verzeichnis {$name} ist nicht beschreibbar (Berechtigungen: {$perms})";
        } else {
            $success[] = "Verzeichnis {$name} existiert und ist beschreibbar (Berechtigungen: {$perms})";
        }
    }
}
