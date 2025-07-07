<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VersionService;
use Illuminate\Support\Facades\File;

class VersionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'version:manage 
                            {action : The action to perform (show|set|bump|build)}
                            {version? : The version to set (when using set action)}
                            {--type= : The type of version bump (major|minor|patch)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage application version';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'show':
                $this->showVersion();
                break;
            case 'set':
                $this->setVersion();
                break;
            case 'bump':
                $this->bumpVersion();
                break;
            case 'build':
                $this->createBuildInfo();
                break;
            default:
                $this->error("Unknown action: {$action}");
                $this->info("Available actions: show, set, bump, build");
                return 1;
        }

        return 0;
    }

    /**
     * Show current version information
     */
    private function showVersion()
    {
        $buildInfo = VersionService::getBuildInfo();

        $this->info("=== Venditio Versionsinformationen ===");
        $this->line("");
        
        $this->info("Aktuelle Version: " . $buildInfo['version']);
        $this->info("Build-Datum: " . $buildInfo['build_date']);
        $this->info("Umgebung: " . $buildInfo['environment']);
        $this->info("PHP-Version: " . $buildInfo['php_version']);
        $this->info("Laravel-Version: " . $buildInfo['laravel_version']);
        
        if ($buildInfo['git_commit']) {
            $this->info("Git-Commit: " . substr($buildInfo['git_commit'], 0, 7));
            $this->info("Git-Branch: " . $buildInfo['git_branch']);
        }
        
        $this->line("");
        $this->info("Anzeige-Version: " . VersionService::getVersionDisplay());
    }

    /**
     * Set specific version
     */
    private function setVersion()
    {
        $version = $this->argument('version');
        
        if (!$version) {
            $version = $this->ask('Welche Version möchten Sie setzen?');
        }
        
        if (!$this->isValidVersion($version)) {
            $this->error("Ungültige Version: {$version}");
            $this->info("Verwenden Sie Semantic Versioning (z.B. 1.0.0)");
            return;
        }
        
        $this->updateComposerVersion($version);
        $this->info("Version erfolgreich auf {$version} gesetzt");
        
        if ($this->confirm('Möchten Sie auch einen Git-Tag erstellen?')) {
            $this->createGitTag($version);
        }
    }

    /**
     * Bump version
     */
    private function bumpVersion()
    {
        $type = $this->option('type');
        
        if (!$type) {
            $type = $this->choice('Welche Art von Version-Bump?', ['patch', 'minor', 'major'], 0);
        }
        
        $currentVersion = VersionService::getCurrentVersion();
        $newVersion = $this->calculateNewVersion($currentVersion, $type);
        
        if (!$newVersion) {
            $this->error("Konnte keine neue Version berechnen");
            return;
        }
        
        $this->info("Aktuelle Version: {$currentVersion}");
        $this->info("Neue Version: {$newVersion}");
        
        if ($this->confirm('Möchten Sie die Version aktualisieren?')) {
            $this->updateComposerVersion($newVersion);
            $this->info("Version erfolgreich auf {$newVersion} erhöht");
            
            if ($this->confirm('Möchten Sie auch einen Git-Tag erstellen?')) {
                $this->createGitTag($newVersion);
            }
        }
    }

    /**
     * Create build info
     */
    private function createBuildInfo()
    {
        VersionService::createBuildInfo();
        $this->info("Build-Info-Datei wurde erstellt");
    }

    /**
     * Validate version format
     */
    private function isValidVersion(string $version): bool
    {
        return preg_match('/^\d+\.\d+\.\d+(-[a-zA-Z0-9.-]+)?$/', $version);
    }

    /**
     * Update composer.json version
     */
    private function updateComposerVersion(string $version)
    {
        $composerPath = base_path('composer.json');
        $composer = json_decode(File::get($composerPath), true);
        
        $composer['version'] = $version;
        
        File::put($composerPath, json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Create git tag
     */
    private function createGitTag(string $version)
    {
        $tagName = "v{$version}";
        $message = $this->ask("Tag-Nachricht für {$tagName}:", "Release {$version}");
        
        try {
            shell_exec("git tag -a {$tagName} -m \"{$message}\"");
            $this->info("Git-Tag {$tagName} wurde erstellt");
            
            if ($this->confirm('Möchten Sie den Tag zum Remote-Repository pushen?')) {
                shell_exec("git push origin {$tagName}");
                $this->info("Tag {$tagName} wurde gepusht");
            }
        } catch (\Exception $e) {
            $this->error("Fehler beim Erstellen des Git-Tags: " . $e->getMessage());
        }
    }

    /**
     * Calculate new version based on bump type
     */
    private function calculateNewVersion(string $currentVersion, string $type): ?string
    {
        // Entferne v-Prefix falls vorhanden
        $version = ltrim($currentVersion, 'v');
        
        // Parse version
        if (!preg_match('/^(\d+)\.(\d+)\.(\d+)/', $version, $matches)) {
            return null;
        }
        
        $major = (int) $matches[1];
        $minor = (int) $matches[2];
        $patch = (int) $matches[3];
        
        switch ($type) {
            case 'major':
                return ($major + 1) . '.0.0';
            case 'minor':
                return $major . '.' . ($minor + 1) . '.0';
            case 'patch':
                return $major . '.' . $minor . '.' . ($patch + 1);
            default:
                return null;
        }
    }
} 