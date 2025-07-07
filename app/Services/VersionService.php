<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class VersionService
{
    /**
     * Get the current application version
     */
    public static function getCurrentVersion(): string
    {
        return Cache::remember('app_version', 3600, function () {
            // Versuche aus verschiedenen Quellen zu lesen
            
            // 1. Aus composer.json
            $composerVersion = self::getComposerVersion();
            if ($composerVersion) {
                return $composerVersion;
            }
            
            // 2. Aus Git Tags
            $gitVersion = self::getGitVersion();
            if ($gitVersion) {
                return $gitVersion;
            }
            
            // 3. Aus Config
            $configVersion = config('app.version');
            if ($configVersion) {
                return $configVersion;
            }
            
            // 4. Fallback
            return '1.0.0';
        });
    }
    
    /**
     * Get version from composer.json
     */
    private static function getComposerVersion(): ?string
    {
        $composerPath = base_path('composer.json');
        
        if (!File::exists($composerPath)) {
            return null;
        }
        
        $composer = json_decode(File::get($composerPath), true);
        
        return $composer['version'] ?? null;
    }
    
    /**
     * Get version from git tags
     */
    private static function getGitVersion(): ?string
    {
        try {
            // Prüfe ob wir in einem Git-Repository sind
            if (!File::exists(base_path('.git'))) {
                return null;
            }
            
            $version = trim(shell_exec('git describe --tags --abbrev=0 2>/dev/null'));
            
            if (empty($version)) {
                // Fallback auf commit hash
                $hash = trim(shell_exec('git rev-parse --short HEAD 2>/dev/null'));
                return $hash ? "dev-{$hash}" : null;
            }
            
            return $version;
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get build information
     */
    public static function getBuildInfo(): array
    {
        return [
            'version' => self::getCurrentVersion(),
            'build_date' => self::getBuildDate(),
            'environment' => app()->environment(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'git_commit' => self::getGitCommit(),
            'git_branch' => self::getGitBranch(),
        ];
    }
    
    /**
     * Get build date
     */
    private static function getBuildDate(): string
    {
        // Aus Build-Info-Datei lesen oder aktuelles Datum verwenden
        $buildInfoPath = base_path('build-info.json');
        
        if (File::exists($buildInfoPath)) {
            $buildInfo = json_decode(File::get($buildInfoPath), true);
            return $buildInfo['build_date'] ?? date('Y-m-d H:i:s');
        }
        
        return date('Y-m-d H:i:s');
    }
    
    /**
     * Get current git commit
     */
    private static function getGitCommit(): ?string
    {
        try {
            if (!File::exists(base_path('.git'))) {
                return null;
            }
            
            return trim(shell_exec('git rev-parse HEAD 2>/dev/null'));
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Get current git branch
     */
    private static function getGitBranch(): ?string
    {
        try {
            if (!File::exists(base_path('.git'))) {
                return null;
            }
            
            return trim(shell_exec('git rev-parse --abbrev-ref HEAD 2>/dev/null'));
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Create build info file
     */
    public static function createBuildInfo(): void
    {
        $buildInfo = [
            'version' => self::getCurrentVersion(),
            'build_date' => date('Y-m-d H:i:s'),
            'git_commit' => self::getGitCommit(),
            'git_branch' => self::getGitBranch(),
            'environment' => app()->environment(),
        ];
        
        File::put(base_path('build-info.json'), json_encode($buildInfo, JSON_PRETTY_PRINT));
    }
    
    /**
     * Compare versions
     */
    public static function compareVersions(string $version1, string $version2): int
    {
        return version_compare($version1, $version2);
    }
    
    /**
     * Check if version is newer than another
     */
    public static function isNewerVersion(string $newVersion, string $currentVersion): bool
    {
        return self::compareVersions($newVersion, $currentVersion) > 0;
    }
    
    /**
     * Get version display string
     */
    public static function getVersionDisplay(): string
    {
        $version = self::getCurrentVersion();
        $buildInfo = self::getBuildInfo();
        
        if ($buildInfo['git_commit'] && app()->environment('local')) {
            $shortCommit = substr($buildInfo['git_commit'], 0, 7);
            return "{$version} ({$shortCommit})";
        }
        
        return $version;
    }
} 