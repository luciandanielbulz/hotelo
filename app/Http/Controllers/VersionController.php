<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VersionService;

class VersionController extends Controller
{
    /**
     * Display version information
     */
    public function index()
    {
        if (!auth()->user()->hasPermission('view_system_info')) {
            abort(403, 'Keine Berechtigung zum Anzeigen der Systeminformationen');
        }
        
        $buildInfo = VersionService::getBuildInfo();
        $version = VersionService::getVersionDisplay();
        
        return view('version.index', compact('buildInfo', 'version'));
    }
    
    /**
     * Get version info as JSON (for API)
     */
    public function api()
    {
        $buildInfo = VersionService::getBuildInfo();
        
        return response()->json([
            'version' => $buildInfo['version'],
            'build_date' => $buildInfo['build_date'],
            'environment' => $buildInfo['environment'],
            'git_commit' => $buildInfo['git_commit'] ? substr($buildInfo['git_commit'], 0, 7) : null,
            'git_branch' => $buildInfo['git_branch'],
        ]);
    }
} 