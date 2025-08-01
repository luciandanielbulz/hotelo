<?php

namespace App\Livewire\ServerMonitoring;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class ServerMonitoringDashboard extends Component
{
    public $serverData = [];
    public $isLoading = true;
    public $lastUpdate = null;
    public $error = null;

    protected $listeners = ['refreshData'];

    public function mount()
    {
        $this->loadServerData();
    }

    public function loadServerData()
    {
        try {
            $this->isLoading = true;
            $this->error = null;

            $response = Http::get(route('server-monitoring.data'));
            
            if ($response->successful()) {
                $this->serverData = $response->json();
                $this->lastUpdate = now()->format('H:i:s');
            } else {
                $this->error = 'Fehler beim Laden der Server-Daten';
            }
        } catch (\Exception $e) {
            $this->error = 'Verbindungsfehler: ' . $e->getMessage();
        } finally {
            $this->isLoading = false;
        }
    }

    public function refreshData()
    {
        $this->loadServerData();
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function formatUptime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($days > 0) {
            return "{$days}d {$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h {$minutes}m";
        } else {
            return "{$minutes}m";
        }
    }

    public function getCpuColor($usage)
    {
        if ($usage < 50) return 'text-green-600';
        if ($usage < 80) return 'text-yellow-600';
        return 'text-red-600';
    }

    public function getMemoryColor($percentage)
    {
        if ($percentage < 70) return 'text-green-600';
        if ($percentage < 90) return 'text-yellow-600';
        return 'text-red-600';
    }

    public function getDiskColor($percentage)
    {
        if ($percentage < 80) return 'text-green-600';
        if ($percentage < 95) return 'text-yellow-600';
        return 'text-red-600';
    }

    public function render()
    {
        return view('livewire.server-monitoring.server-monitoring-dashboard');
    }
} 