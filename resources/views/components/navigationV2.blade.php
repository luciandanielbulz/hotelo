<!-- Modernisierte Navigation mit Glassmorphism -->
<nav x-data="{ 
    open: false, 
    offersOpen: false, 
    invoicesOpen: false, 
    adminOpen: false,
    profileOpen: false,
    salesOpen: false,
    scrolled: false 
}" 
@scroll.window="scrolled = window.pageYOffset > 20"
:class="scrolled ? 'bg-white/80 backdrop-blur-lg shadow-lg border-b border-white/20' : 'bg-white/60 backdrop-blur-sm'"
class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    
    <!-- Hauptnavigationsmenü -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 2xl:max-w-screen-2xl">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo mit modernem Effekt -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center space-x-3">
                                                                                                                                                                                                           <img src="{{ asset('logo/VenditioLogo.png') }}" alt="Venditio" class="h-24 w-24" style="height: 50px; width: 120px; margin-top: 2px;" />
                    </a>
                </div>

                <!-- Desktop Navigationslinks -->
                <div class="hidden lg:ml-4 lg:flex lg:space-x-2">


                    <!-- Kunden -->
                    @if(auth()->user()->hasPermission('view_customers'))
                        <a href="{{ route('customer.index') }}" 
                           class="group relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/30 hover:backdrop-blur-sm {{ request()->routeIs('customer.*') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 shadow-sm border border-white/40' : 'text-gray-700 hover:text-gray-900' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <span>Kunden</span>
                            </div>
                        </a>
                    @endif

                    <!-- Angebote Dropdown -->
                    @if(auth()->user()->hasPermission('view_offers'))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="group relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/30 hover:backdrop-blur-sm {{ request()->routeIs('offer.*') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 shadow-sm border border-white/40' : 'text-gray-700 hover:text-gray-900' }} flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Angebote</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 @click.away="open = false"
                                 class="absolute top-full left-0 mt-2 w-56 bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 py-2"
                                 style="display: none;">
                                <a href="{{ route('offer.index') }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Angebote</div>
                                        <div class="text-xs text-gray-500">Alle aktiven Angebote</div>
                                    </div>
                                </a>
                                <a href="{{ route('offer.index_archivated') }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Archivierte</div>
                                        <div class="text-xs text-gray-500">Abgeschlossene Angebote</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Rechnungen Dropdown -->
                    @if(auth()->user()->hasPermission('view_invoices'))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="group relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/30 hover:backdrop-blur-sm {{ request()->routeIs('invoice.*') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 shadow-sm border border-white/40' : 'text-gray-700 hover:text-gray-900' }} flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Rechnungen</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 @click.away="open = false"
                                 class="absolute top-full left-0 mt-2 w-64 bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 py-2"
                                 style="display: none;">
                                <a href="{{ route('invoice.index') }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Rechnungen</div>
                                        <div class="text-xs text-gray-500">Alle aktiven Rechnungen</div>
                                    </div>
                                </a>
                                <a href="{{ route('invoice.index_archivated') }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-gray-400 to-gray-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6 6-6" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Archivierte</div>
                                        <div class="text-xs text-gray-500">Abgeschlossene Rechnungen</div>
                                    </div>
                                </a>
                                <a href="{{ route('invoiceupload.index') }}" 
                                   class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Ausgabenverwaltung</div>
                                        <div class="text-xs text-gray-500">Rechnungen hochladen</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif



                    <!-- Umsatzauswertung Dropdown -->
                    @if(auth()->user()->hasPermission('view_sales_analysis'))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="group relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/30 hover:backdrop-blur-sm {{ request()->routeIs('sales.*') || request()->routeIs('bankdata.*') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 shadow-sm border border-white/40' : 'text-gray-700 hover:text-gray-900' }} flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Umsatzauswertung</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 class="absolute top-full left-0 mt-2 w-56 bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 py-2"
                                 style="display: none;">
                                <div class="py-2">
                                    <a href="{{ route('sales.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Umsatz</div>
                                            <div class="text-xs text-gray-500">Umsatzauswertung</div>
                                        </div>
                                    </a>
                                    <a href="{{ route('bankdata.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Ausgabenliste</div>
                                            <div class="text-xs text-gray-500">Alle Ausgaben anzeigen</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Postausgang -->
                    @if(auth()->user()->hasPermission('view_messages'))
                        <a href="{{ route('outgoingemails.index') }}" 
                           class="group relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/30 hover:backdrop-blur-sm {{ request()->routeIs('outgoingemails.*') ? 'bg-gradient-to-r from-blue-50 to-purple-50 text-blue-700 shadow-sm border border-white/40' : 'text-gray-700 hover:text-gray-900' }}">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span>Postausgang</span>
                            </div>
                        </a>
                    @endif

                    <!-- Admin Dropdown -->
                    @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_permissions') || auth()->user()->hasPermission('view_clients') || auth()->user()->hasPermission('manage_users') || auth()->user()->hasPermission('view_conditions') || auth()->user()->hasPermission('edit_my_client_settings') || auth()->user()->hasPermission('view_client_versions') || auth()->user()->hasPermission('manage_maintenance') || auth()->user()->hasPermission('manage_currencies') || auth()->user()->hasPermission('manage_categories'))
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="group relative px-4 py-2 rounded-xl text-sm font-medium transition-all duration-300 hover:bg-white/30 hover:backdrop-blur-sm text-gray-700 hover:text-gray-900 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Administration</span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            
                            <!-- Admin Dropdown Menu -->
                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 translate-y-1"
                                 @click.away="open = false"
                                 class="absolute top-full right-0 mt-2 w-72 bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 py-3"
                                 style="display: none;">
                                
                                <!-- Benutzerverwaltung Sektion -->
                                <div class="px-4 py-2">
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Benutzerverwaltung</h3>
                                </div>
                                
                                @if(auth()->user()->hasPermission('manage_roles'))
                                    <a href="{{ route('roles.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Rollen</div>
                                            <div class="text-xs text-gray-500">Benutzerrollen verwalten</div>
                                        </div>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('manage_permissions'))
                                    <a href="{{ route('permissions.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-teal-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.012-3.014l.976-1.409a2.036 2.036 0 012.96 2.73L21 12l-9.39 9.39a2 2 0 01-2.828 0L3.172 15.78a4 4 0 010-5.656l6.717-6.717" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Rechte</div>
                                            <div class="text-xs text-gray-500">Berechtigungen verwalten</div>
                                        </div>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('manage_users'))
                                    <a href="{{ route('users.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Benutzer</div>
                                            <div class="text-xs text-gray-500">Benutzer verwalten</div>
                                        </div>
                                    </a>
                                @endif

                                <!-- System Einstellungen Sektion -->
                                <div class="px-4 py-2 mt-4 border-t border-gray-100">
                                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">System Einstellungen</h3>
                                </div>

                                @if(auth()->user()->hasPermission('view_clients'))
                                    <a href="{{ route('clients.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Klienten</div>
                                            <div class="text-xs text-gray-500">Client-Verwaltung</div>
                                        </div>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('view_conditions'))
                                    <a href="{{ route('condition.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Konditionen</div>
                                            <div class="text-xs text-gray-500">Zahlungskonditionen</div>
                                        </div>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('manage_currencies'))
                                    <a href="{{ route('currencies.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-green-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Währungen</div>
                                            <div class="text-xs text-gray-500">Währung verwalten</div>
                                        </div>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('manage_categories'))
                                    <a href="{{ route('categories.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-rose-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Kategorien</div>
                                            <div class="text-xs text-gray-500">Kategorie verwalten</div>
                                        </div>
                                    </a>
                                @endif

                                <!-- Client Einstellungen Sektion -->
                                @if(auth()->user()->hasPermission('edit_my_client_settings') || auth()->user()->hasPermission('edit_client_settings') || auth()->user()->hasPermission('view_client_versions'))
                                    <div class="px-4 py-2 mt-4 border-t border-gray-100">
                                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Client Einstellungen</h3>
                                    </div>

                                    @if(auth()->user()->hasPermission('edit_my_client_settings'))
                                        <a href="{{ route('clients.my-settings') }}" 
                                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h1a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium">Firmendaten</div>
                                                <div class="text-xs text-gray-500">Ihre Firmeninformationen</div>
                                            </div>
                                        </a>
                                    @endif
                                @endif

                                <!-- Wartung Sektion -->
                                @if(auth()->user()->hasPermission('manage_maintenance') || auth()->user()->hasPermission('view_server_monitoring'))
                                    <div class="px-4 py-2 mt-4 border-t border-gray-100">
                                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">System</h3>
                                    </div>

                                    @if(auth()->user()->hasPermission('manage_maintenance'))
                                    <a href="{{ route('maintenance.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Wartungsmodus</div>
                                            <div class="text-xs text-gray-500">System Wartung</div>
                                        </div>
                                    </a>
                                @endif

                                @if(auth()->user()->hasPermission('view_server_monitoring'))
                                    <a href="{{ route('server-monitoring.index') }}" 
                                       class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                                        <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium">Server-Monitoring</div>
                                            <div class="text-xs text-gray-500">System-Überwachung</div>
                                        </div>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <!-- Version Badge - ausgeblendet bei kleineren Bildschirmen -->
                <div class="hidden xl:flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full border border-white/30">
                    <span class="text-xs font-medium text-gray-600">V{{ app(\App\Services\VersionService::class)->getCurrentVersion() }}</span>
                </div>

                <!-- Profil Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-3 p-2 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 transition-all duration-300 group">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="hidden xl:flex flex-col items-start">
                            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 transition-transform duration-200 hidden xl:block" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Profil Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         @click.away="open = false"
                         class="absolute top-full right-0 mt-2 w-64 bg-white/95 backdrop-blur-lg rounded-2xl shadow-xl border border-white/20 py-2"
                         style="display: none;">
                        
                        <!-- Benutzer Info -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center">
                                    <span class="text-white font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                    <div class="text-sm text-gray-500">{{ auth()->user()->email }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Profil Link -->
                        <a href="{{ route('profile.edit') }}" 
                           class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 flex items-center space-x-3 mx-2 rounded-xl">
                            <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-medium">Profil</div>
                                <div class="text-xs text-gray-500">Einstellungen bearbeiten</div>
                            </div>
                        </a>

                        <!-- Abmeldung -->
                        <div class="border-t border-gray-100 mx-2 mt-2 pt-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50/50 transition-all duration-200 flex items-center space-x-3 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">Abmelden</div>
                                        <div class="text-xs text-gray-400">Session beenden</div>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div class="lg:hidden">
                    <button @click="open = !open" 
                            class="p-2 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 hover:bg-white/30 transition-all duration-300">
                        <svg class="w-6 h-6 text-gray-600" :class="open ? 'hidden' : 'block'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="w-6 h-6 text-gray-600" :class="open ? 'block' : 'hidden'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="lg:hidden bg-white/95 backdrop-blur-lg border-t border-white/20"
         style="display: none;">
        <div class="px-4 py-6 space-y-3">


            <!-- Mobile Kunden -->
            @if(auth()->user()->hasPermission('view_customers'))
                <a href="{{ route('customer.index') }}" 
                   class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('customer.*') ? 'bg-blue-50/50 text-blue-700' : '' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <span class="font-medium">Kunden</span>
                    </div>
                </a>
            @endif

            <!-- Mobile Angebote -->
            @if(auth()->user()->hasPermission('view_offers'))
                <div x-data="{ expanded: false }" class="space-y-2">
                    <button @click="expanded = !expanded" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('offer.*') ? 'bg-blue-50/50 text-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Angebote</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="expanded" x-transition class="pl-4 space-y-2" style="display: none;">
                        <a href="{{ route('offer.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Alle Angebote</a>
                        <a href="{{ route('offer.index_archivated') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Archivierte</a>
                    </div>
                </div>
            @endif

            <!-- Mobile Rechnungen -->
            @if(auth()->user()->hasPermission('view_invoices'))
                <div x-data="{ expanded: false }" class="space-y-2">
                    <button @click="expanded = !expanded" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('invoice.*') ? 'bg-blue-50/50 text-blue-700' : '' }}">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Rechnungen</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="expanded" x-transition class="pl-4 space-y-2" style="display: none;">
                        <a href="{{ route('invoice.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Alle Rechnungen</a>
                        <a href="{{ route('invoice.index_archivated') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Archivierte</a>
                        <a href="{{ route('invoiceupload.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Ausgabenverwaltung</a>
                    </div>
                </div>
            @endif

            <!-- Mobile Umsatzauswertung -->
            @if(auth()->user()->hasPermission('view_sales_analysis'))
                <div x-data="{ salesExpanded: false }" class="space-y-1">
                    <button @click="salesExpanded = !salesExpanded" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="font-medium">Umsatzauswertung</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': salesExpanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7 7" />
                        </svg>
                    </button>
                    <div x-show="salesExpanded" x-transition class="pl-4 space-y-2" style="display: none;">
                        <a href="{{ route('sales.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Umsatz</a>
                        <a href="{{ route('bankdata.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 transition-colors duration-200">Ausgabenliste</a>
                    </div>
                </div>
            @endif

            @if(auth()->user()->hasPermission('view_messages'))
                <a href="{{ route('outgoingemails.index') }}" 
                   class="block px-4 py-3 rounded-xl text-gray-700 hover:bg-blue-50/50 hover:text-blue-700 transition-all duration-200 {{ request()->routeIs('outgoingemails.*') ? 'bg-blue-50/50 text-blue-700' : '' }}">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">Postausgang</span>
                    </div>
                </a>
            @endif
        </div>
    </div>
</nav>

<!-- Spacer für fixed Navigation -->
<div class="h-16"></div>
