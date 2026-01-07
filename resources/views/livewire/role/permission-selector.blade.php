<div>
    <div class="space-y-4">
        <!-- Header mit Statistiken -->
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Berechtigungen verwalten
                </div>
            </h3>
            <div class="hidden sm:block text-sm text-gray-500">
                <span class="font-medium text-blue-900">{{ $selectedCount }}</span> von {{ $totalCount }} ausgewählt
            </div>
        </div>

        <!-- Suchfeld und Filter -->
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input wire:model.live="search" type="text" placeholder="Berechtigungen oder Kategorien suchen..." 
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent shadow-sm bg-white text-gray-900 placeholder-gray-500 sm:text-sm">
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <label class="flex items-center">
                    <input wire:model.live="showOnlySelected" type="checkbox" class="rounded border-gray-300 text-blue-900 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Nur ausgewählte</span>
                </label>
            </div>
        </div>

        <!-- Schnellaktionen -->
        <div class="hidden sm:flex justify-between items-center">
            <div class="flex space-x-2">
                <button wire:click="selectAll" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                    </svg>
                    Alle auswählen
                </button>
                <button wire:click="deselectAll" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Gefilterte abwählen
                </button>
                @if($selectedCount > 0)
                    <button wire:click="clearAll" type="button" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Alle löschen
                    </button>
                @endif
            </div>
            
            <div class="flex space-x-2">
                <button wire:click="expandAllCategories" type="button" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm font-medium rounded-md text-blue-900 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    Alle erweitern
                </button>
                <button wire:click="collapseAllCategories" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                    </svg>
                    Alle einklappen
                </button>
            </div>
        </div>

        <!-- Gruppierte Permissions Liste -->
        <div class="space-y-4">
            @if($groupedPermissions->count() > 0)
                @foreach($groupedPermissions as $category => $permissions)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <!-- Category Header -->
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 rounded-t-lg">
                            <div class="flex items-center justify-between">
                                <button wire:click="toggleCategory('{{ $category }}')" type="button" class="flex items-center space-x-2 text-left">
                                    <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 {{ in_array($category, $expandedCategories) ? 'rotate-90' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    <h4 class="text-md font-semibold text-gray-900">{{ $category }}</h4>
                                    <span class="text-sm text-gray-500">({{ $permissions->count() }} Berechtigungen)</span>
                                </button>
                                
                                <div class="hidden sm:flex space-x-2">
                                    <button wire:click="selectAllInCategory('{{ $category }}')" type="button" class="text-xs text-blue-900 hover:text-indigo-800">
                                        Alle auswählen
                                    </button>
                                    <button wire:click="deselectAllInCategory('{{ $category }}')" type="button" class="text-xs text-red-600 hover:text-red-800">
                                        Alle abwählen
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Category Content -->
                        @if(in_array($category, $expandedCategories))
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($permissions as $permission)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md hover:bg-gray-100 transition-colors duration-150">
                                            <div class="flex items-center min-w-0 flex-1 cursor-pointer" wire:click="togglePermission({{ $permission->id }})">
                                                <div class="flex-shrink-0">
                                                    <input 
                                                        type="checkbox" 
                                                        name="permissions[]" 
                                                        value="{{ $permission->id }}"
                                                        {{ in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-blue-900 focus:ring-blue-700 border-gray-300 rounded pointer-events-none">
                                                </div>
                                                <div class="ml-3 min-w-0 flex-1">
                                                    @if($permission->description)
                                                        <label class="text-sm font-medium text-gray-900 cursor-pointer block">
                                                            {{ $permission->description }}
                                                        </label>
                                                        <p class="text-xs text-gray-500">{{ $permission->name }}</p>
                                                    @else
                                                        <label class="text-sm font-medium text-gray-900 cursor-pointer block">
                                                            {{ $permission->name }}
                                                        </label>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            @if(in_array($permission->id, $selectedPermissions))
                                                <div class="flex-shrink-0">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Aktiv
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Berechtigungen gefunden</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search)
                            Versuchen Sie einen anderen Suchbegriff.
                        @else
                            Es sind keine Berechtigungen verfügbar.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Hidden inputs für das Formular -->
        @foreach($selectedPermissions as $permissionId)
            <input type="hidden" name="permissions[]" value="{{ $permissionId }}">
        @endforeach
    </div>
</div> 