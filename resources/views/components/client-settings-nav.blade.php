@props(['active' => ''])

@php
    $user = Auth::user();
    $client = App\Models\Clients::active()->where('id', $user->client_id)->first();
    
    $navItems = [];
    
    // Firmendaten (für alle mit edit_my_client_settings Berechtigung)
    if ($user->hasPermission('edit_my_client_settings')) {
        $navItems[] = [
            'route' => 'clients.my-settings',
            'key' => 'my-settings',
            'title' => 'Firmendaten',
            'description' => 'Versionierte Einstellungen',
            'icon' => 'building',
            'color' => 'blue'
        ];
    }
    
    // Statische Einstellungen (für alle mit edit_client_settings Berechtigung)
    if ($user->hasPermission('edit_client_settings')) {
        $navItems[] = [
            'route' => 'client-settings.edit',
            'key' => 'client-settings',
            'title' => 'Statische Einstellungen',
            'description' => 'Nummerierung & Präfixe',
            'icon' => 'calculator',
            'color' => 'orange'
        ];
    }
    
    // Versionshistorie (für alle mit view_client_versions Berechtigung)
    if ($user->hasPermission('view_client_versions') && $client) {
        $navItems[] = [
            'route' => 'clients.versions',
            'key' => 'versions',
            'title' => 'Versionshistorie',
            'description' => 'Alle Client-Versionen',
            'icon' => 'history',
            'color' => 'purple',
            'params' => [$client->id]
        ];
    }
    
    // Admin: Client-Verwaltung (für Admins mit manage_all_clients Berechtigung)
    if ($user->hasPermission('manage_all_clients')) {
        $navItems[] = [
            'route' => 'clients.index',
            'key' => 'admin-clients',
            'title' => 'Client-Verwaltung',
            'description' => 'Alle Clients verwalten',
            'icon' => 'users',
            'color' => 'red'
        ];
    }

    // Icon-Mapping
    $icons = [
        'building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        'calculator' => 'M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        'history' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4 4 0 11-8 0 4 4 0 018 0z'
    ];
    
    // Farbklassen-Mapping
    $colorClasses = [
        'blue' => [
            'active' => 'border-blue-500 text-blue-600 bg-blue-50 shadow-sm',
            'inactive' => 'border-transparent text-gray-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50'
        ],
        'orange' => [
            'active' => 'border-orange-500 text-orange-600 bg-orange-50 shadow-sm',
            'inactive' => 'border-transparent text-gray-500 hover:text-orange-600 hover:border-orange-300 hover:bg-orange-50'
        ],
        'purple' => [
            'active' => 'border-purple-500 text-purple-600 bg-purple-50 shadow-sm',
            'inactive' => 'border-transparent text-gray-500 hover:text-purple-600 hover:border-purple-300 hover:bg-purple-50'
        ],
        'red' => [
            'active' => 'border-red-500 text-red-600 bg-red-50 shadow-sm',
            'inactive' => 'border-transparent text-gray-500 hover:text-red-600 hover:border-red-300 hover:bg-red-50'
        ]
    ];
@endphp

@if(count($navItems) > 1)
<div class="bg-white shadow-sm border border-gray-200 rounded-lg mb-6 transition-all duration-200 hover:shadow-md">
    <div class="px-4 py-5 sm:p-6">
        <div class="flex items-center mb-3">
            <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Einstellungs-Bereiche</h3>
        </div>
        
        <!-- Desktop Navigation -->
        <div class="hidden sm:block">
            <nav class="flex space-x-1 p-1 bg-gray-100 rounded-lg">
                @foreach($navItems as $item)
                    @php
                        $isActive = $active === $item['key'];
                        $colorClass = $colorClasses[$item['color']][$isActive ? 'active' : 'inactive'];
                        $url = isset($item['params']) 
                            ? route($item['route'], $item['params']) 
                            : route($item['route']);
                    @endphp
                    
                    <a href="{{ $url }}" 
                       class="group flex-1 inline-flex items-center justify-center py-3 px-4 border-b-2 font-medium text-sm {{ $colorClass }} transition-all duration-200 ease-in-out rounded-md transform hover:scale-105">
                        <svg class="mr-2 h-5 w-5 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icons[$item['icon']] }}"></path>
                        </svg>
                        <div class="flex flex-col items-center">
                            <span class="font-medium">{{ $item['title'] }}</span>
                            <span class="text-xs opacity-75 transition-opacity duration-200 group-hover:opacity-100">{{ $item['description'] }}</span>
                        </div>
                    </a>
                @endforeach
            </nav>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="sm:hidden">
            <label for="client-settings-tab" class="sr-only">Bereich auswählen</label>
            <select id="client-settings-tab" name="client-settings-tab" 
                    class="block w-full rounded-md border-gray-300 focus:border-blue-900 focus:ring-blue-700 transition-all duration-200"
                    onchange="window.location.href = this.value">
                @foreach($navItems as $item)
                    @php
                        $url = isset($item['params']) 
                            ? route($item['route'], $item['params']) 
                            : route($item['route']);
                        $isSelected = $active === $item['key'];
                    @endphp
                    <option value="{{ $url }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $item['title'] }} - {{ $item['description'] }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- Berechtigungs-Hinweis für normale Benutzer -->
        @if(!$user->hasPermission('edit_client_settings') && $user->hasPermission('edit_my_client_settings'))
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md transition-all duration-200 hover:bg-blue-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Hinweis:</strong> Statische Einstellungen (Nummerierung, Präfixe) finden Sie im separaten "Einstellungen"-Menüpunkt der Hauptnavigation.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Hinweis für Benutzer mit statischen Einstellungen -->
        @if($user->hasPermission('edit_client_settings'))
            <div class="mt-4 p-3 bg-orange-50 border border-orange-200 rounded-md transition-all duration-200 hover:bg-orange-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-orange-700">
                            <strong>Info:</strong> Statische Einstellungen haben einen eigenen Menüpunkt in der Hauptnavigation für bessere Übersichtlichkeit.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Admin-Hinweis -->
        @if($user->hasPermission('manage_all_clients'))
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-md transition-all duration-200 hover:bg-amber-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-amber-700">
                            <strong>Administrator-Bereich:</strong> Sie haben Zugriff auf erweiterte Einstellungen und alle Client-Daten.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Schnellzugriff-Hinweise für verschiedene Rollen -->
        @if(count($navItems) > 2)
            <div class="mt-4 text-center">
                <div class="inline-flex items-center text-xs text-gray-500">
                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Schnellzugriff: Verwenden Sie <kbd class="px-1 py-0.5 bg-gray-200 rounded text-xs">Tab</kbd> zum Navigieren
                </div>
            </div>
        @endif
    </div>
</div>

<!-- CSS für bessere Animationen -->
<style>
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.client-settings-nav {
    animation: fadeInScale 0.3s ease-out;
}

kbd {
    box-shadow: 0 1px 1px rgba(0,0,0,0.2), 0 2px 0 0 rgba(255,255,255,0.7) inset;
}
</style>
@endif 