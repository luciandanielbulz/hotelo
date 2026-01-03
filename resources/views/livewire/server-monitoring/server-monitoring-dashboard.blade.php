<div class="space-y-6" @if($autoRefresh) wire:poll.5s="loadServerData" @endif>
    <!-- Auto-Refresh Status -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-2">
            @if($autoRefresh)
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-gray-600">Live-Updates aktiv</span>
            @else
                <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                <span class="text-sm text-gray-600">Live-Updates inaktiv</span>
            @endif
        </div>
        <div class="flex items-center space-x-4">
            <button wire:click="toggleAutoRefresh" 
                    class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                {{ $autoRefresh ? 'Auto-Refresh aus' : 'Auto-Refresh an' }}
            </button>
            <button wire:click="loadServerData" 
                    class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Aktualisieren
            </button>
            @if($lastUpdate)
                <span class="text-xs text-gray-500">
                    Letzte Aktualisierung: {{ $lastUpdate }}
                </span>
            @endif
        </div>
    </div>

    <!-- System-Übersicht -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- CPU-Auslastung -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500">CPU-Auslastung</h3>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold {{ $this->getCpuColor($serverData['cpu'] ?? 0) }} transition-all duration-300">
                                {{ number_format($serverData['cpu'] ?? 0, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-2 rounded-full" 
                             style="width: {{ min($serverData['cpu'] ?? 0, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Speicherauslastung -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500">Speicherauslastung</h3>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold {{ $this->getMemoryColor($serverData['memory']['percentage'] ?? 0) }} transition-all duration-300">
                                {{ number_format($serverData['memory']['percentage'] ?? 0, 1) }}%
                            </p>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ $this->formatBytes($serverData['memory']['used'] ?? 0) }} / {{ $this->formatBytes($serverData['memory']['total'] ?? 0) }}
                        </p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full" 
                             style="width: {{ min($serverData['memory']['percentage'] ?? 0, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Uptime -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500">Uptime</h3>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $this->formatUptime($serverData['uptime'] ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load Average (nur Linux) -->
        @if(isset($serverData['load_average']) && is_array($serverData['load_average']))
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-500">Load Average</h3>
                        <div class="flex items-baseline">
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ number_format($serverData['load_average'][0] ?? 0, 2) }}
                            </p>
                        </div>
                        <p class="text-sm text-gray-500">
                            1m: {{ number_format($serverData['load_average'][0] ?? 0, 2) }} | 
                            5m: {{ number_format($serverData['load_average'][1] ?? 0, 2) }} | 
                            15m: {{ number_format($serverData['load_average'][2] ?? 0, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Festplattenauslastung -->
    @if(isset($serverData['disk']) && is_array($serverData['disk']))
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Festplattenauslastung</h3>
            <div class="space-y-4">
                @foreach($serverData['disk'] as $disk)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-700">{{ $disk['device'] }}</h4>
                        <span class="text-sm font-semibold {{ $this->getDiskColor($disk['percentage']) }}">
                            {{ number_format($disk['percentage'], 1) }}%
                        </span>
                    </div>
                    <div class="bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-3 rounded-full" 
                             style="width: {{ min($disk['percentage'], 100) }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                        <span>Verwendet: {{ $this->formatBytes($disk['used']) }}</span>
                        <span>Frei: {{ $this->formatBytes($disk['free']) }}</span>
                        <span>Gesamt: {{ $this->formatBytes($disk['total']) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Netzwerk-Statistiken -->
    @if(isset($serverData['network']))
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Netzwerk-Statistiken</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-700">Empfangen</h4>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $this->formatBytes($serverData['network']['bytes_received'] ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-gray-700">Gesendet</h4>
                            <p class="text-2xl font-semibold text-gray-900">
                                {{ $this->formatBytes($serverData['network']['bytes_sent'] ?? 0) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- System-Informationen -->
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">System-Informationen</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Betriebssystem</h4>
                    <p class="text-sm text-gray-900">{{ PHP_OS_FAMILY === 'Windows' ? 'Windows' : 'Linux' }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">PHP-Version</h4>
                    <p class="text-sm text-gray-900">{{ PHP_VERSION }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Laravel-Version</h4>
                    <p class="text-sm text-gray-900">{{ app()->version() }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Zeitzone</h4>
                    <p class="text-sm text-gray-900">{{ config('app.timezone') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Datenbank-Informationen -->
    @if(isset($serverData['database']))
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Datenbank-Informationen</h3>
                @if($serverData['database']['connected'] ?? false)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                        Verbunden
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-red-400 rounded-full"></span>
                        Nicht verbunden
                    </span>
                @endif
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Datenbank-Typ</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $serverData['database']['driver'] ?? 'N/A' }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Version</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $serverData['database']['version'] ?? 'N/A' }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Datenbank-Name</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $serverData['database']['database_name'] ?? 'N/A' }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Tabellen</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $serverData['database']['table_count'] ?? 0 }}</p>
                </div>
                
                <div class="bg-gradient-to-r from-pink-50 to-rose-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Datenbank-Größe</h4>
                    <p class="text-lg font-semibold text-gray-900">{{ $serverData['database']['size_formatted'] ?? 'N/A' }}</p>
                </div>
                
                @if(isset($serverData['database']['max_connections']) && $serverData['database']['max_connections'] !== null)
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-1">Verbindungen</h4>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $serverData['database']['current_connections'] ?? 0 }} / {{ $serverData['database']['max_connections'] }}
                    </p>
                    @if($serverData['database']['max_connections'] > 0)
                        @php
                            $connectionPercentage = ($serverData['database']['current_connections'] ?? 0) / $serverData['database']['max_connections'] * 100;
                        @endphp
                        <div class="mt-2 bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-yellow-500 to-amber-500 h-2 rounded-full" 
                                 style="width: {{ min($connectionPercentage, 100) }}%"></div>
                        </div>
                    @endif
                </div>
                @endif
            </div>
            
            @if(isset($serverData['database']['error']))
            <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-red-800">Fehler beim Abrufen der Datenbank-Informationen</h4>
                        <p class="mt-1 text-sm text-red-700">{{ $serverData['database']['error'] }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div> 