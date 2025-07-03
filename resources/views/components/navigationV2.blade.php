<nav x-data="{ open: false }" class="bg-gray-800">
    <!-- Hauptnavigationsmenü -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current" />
                    </a>
                </div>

                <!-- Navigationslinks -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->hasPermission('view_customers'))
                        <x-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.index')">
                            {{ __('Kunden') }}
                        </x-nav-link>
                    @endif



                    @if(auth()->user()->hasPermission('view_offers'))
                        <div x-data="{ open: false }" class="relative sm:-my-px sm:flex">
                            <a href="#" @click.prevent="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('offer.*') ? 'border-indigo-400 text-white' : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                <span>{{ __('Angebote') }}</span>
                            </a>
                            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-5 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    @if(auth()->user()->hasPermission('view_offers'))
                                        <x-dropdown-link :href="route('offer.index')" :active="request()->routeIs('offer.index')">
                                            {{ __('Abgebote') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view_offers'))
                                        <x-dropdown-link :href="route('offer.index_archivated')" :active="request()->routeIs('offer.index_archivated')">
                                            {{ __('Archivierte Abgebote') }}
                                        </x-dropdown-link>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->hasPermission('view_invoices'))
                        <div x-data="{ open: false }" class="relative sm:-my-px sm:flex">
                            <a href="#" @click.prevent="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('invoice.*') ? 'border-indigo-400 text-white' : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                <span>{{ __('Rechnungen') }}</span>
                            </a>
                            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-5 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    @if(auth()->user()->hasPermission('view_invoices'))
                                        <x-dropdown-link :href="route('invoice.index')" :active="request()->routeIs('invoice.index')">
                                            {{ __('Rechnungen') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view_invoices'))
                                        <x-dropdown-link :href="route('invoice.index_archivated')" :active="request()->routeIs('invoice.index_archivated')">
                                            {{ __('Archivierte Rechnungen') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view_invoices'))
                                        <x-dropdown-link :href="route('invoiceupload.index')" :active="request()->routeIs('invoiceupload.index')">
                                            {{ __('Ausgabenverwaltung') }}
                                        </x-dropdown-link>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->hasPermission('view_sales_analysis'))
                        <div x-data="{ open: false }" class="relative sm:-my-px sm:flex">
                            <a href="#" @click.prevent="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('sales.*') ? 'border-indigo-400 text-white' : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                <span>{{ __('Umsatzauswertung') }}</span>
                            </a>
                            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-5 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    @if(auth()->user()->hasPermission('view_sales_analysis'))
                                        <x-dropdown-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                                            {{ __('Umsatz') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view_sales_analysis'))
                                        <x-dropdown-link :href="route('bankdata.upload.form')" :active="request()->routeIs('bankdata.upload.form')">
                                            {{ __('Bankdatenupload') }}
                                        </x-dropdown-link>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif



                    @if(auth()->user()->hasPermission('view_messages'))
                        <x-nav-link :href="route('outgoingemails.index')" :active="request()->routeIs('outgoingemails.index')">
                            {{ __('Postausgang') }}
                        </x-nav-link>
                    @endif



                    <!-- Rollenverwaltung mit Untermenü -->
                    @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_permissions') || auth()->user()->hasPermission('view_clients') || auth()->user()->hasPermission('manage_users') || auth()->user()->hasPermission('view_conditions') || auth()->user()->hasPermission('edit_my_client_settings') || auth()->user()->hasPermission('view_client_versions') || auth()->user()->hasPermission('manage_maintenance') || auth()->user()->hasPermission('manage_currencies'))
                        <div x-data="{ open: false }" class="relative sm:-my-px sm:flex">
                            <a href="#" @click.prevent="open = !open" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('roles.*', 'permissions.*', 'clients.index', 'clients.edit', 'clients.show', 'clients.my-settings', 'clients.versions', 'users.*','logos.*', 'condition.*', 'currencies.*') ? 'border-indigo-400 text-white' : 'border-transparent text-white hover:text-gray-200 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                <span>{{ __('App-Einstellungen') }}</span>
                            </a>
                            <!-- Dropdown-Inhalt -->
                            <div x-show="open" @click.away="open = false" class="absolute z-50 mt-5 w-72 right-0 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    @if(auth()->user()->hasPermission('manage_roles'))
                                        <x-dropdown-link :href="route('roles.index')">
                                            {{ __('Rollen') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('manage_permissions'))
                                        <x-dropdown-link :href="route('permissions.index')">
                                            {{ __('Rechte') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view_clients'))
                                        <x-dropdown-link :href="route('clients.index')">
                                            {{ __('Klienten') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('view_conditions'))
                                        <x-dropdown-link :href="route('condition.index')" :active="request()->routeIs('condition.index')">
                                            {{ __('Konditionen') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('manage_currencies'))
                                        <x-dropdown-link :href="route('currencies.index')" :active="request()->routeIs('currencies.*')">
                                            {{ __('Währungen') }}
                                        </x-dropdown-link>
                                    @endif
                                    @if(auth()->user()->hasPermission('manage_users'))
                                        <x-dropdown-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                                            {{ __('Benutzer') }}
                                        </x-dropdown-link>
                                    @endif

                                    <!-- Client-Einstellungen Sektion -->
                                    @if(auth()->user()->hasPermission('edit_my_client_settings'))
                                        <div class="border-t border-gray-200 my-1"></div>
                                        <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            {{ __('Client-Einstellungen') }}
                                        </div>

                                        <x-dropdown-link :href="route('clients.my-settings')" :active="request()->routeIs('clients.my-settings')">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">{{ __('Firmendaten') }}</div>
                                                    <div class="text-xs text-gray-500">Versionierte Einstellungen</div>
                                                </div>
                                            </div>
                                        </x-dropdown-link>

                                        @php
                                            $client = App\Models\Clients::active()->where('id', auth()->user()->client_id)->first();
                                        @endphp
                                        @if($client && auth()->user()->hasPermission('view_client_versions'))
                                            <x-dropdown-link :href="route('clients.versions', $client->id)" :active="request()->routeIs('clients.versions')">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">{{ __('Versionshistorie') }}</div>
                                                        <div class="text-xs text-gray-500">Alle Client-Versionen</div>
                                                    </div>
                                                </div>
                                            </x-dropdown-link>
                                        @endif

                                        <!-- Statische Einstellungen -->
                                        @if(auth()->user()->hasPermission('edit_client_settings'))
                                            <x-dropdown-link :href="route('client-settings.edit')" :active="request()->routeIs('client-settings.*')">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">{{ __('Einstellungen') }}</div>
                                                        <div class="text-xs text-gray-500">Statische Einstellungen</div>
                                                    </div>
                                                </div>
                                            </x-dropdown-link>
                                        @endif
                                    @endif

                                    <!-- Wartungsmodus -->
                                    @if(auth()->user()->hasPermission('manage_maintenance'))
                                        <div class="border-t border-gray-300 mx-4 my-2"></div>
                                        <div class="px-4 py-2 text-xs font-semibold text-gray-500">System</div>
                                        
                                        <x-dropdown-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-3 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <div>
                                                    <div class="font-medium">{{ __('Wartungsmodus') }}</div>
                                                    <div class="text-xs text-gray-500">App-Wartung verwalten</div>
                                                </div>
                                            </div>
                                        </x-dropdown-link>
                                    @endif

                                    <!-- Weitere Untermenüpunkte -->
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Ende des Untermenüs -->
                </div>


            <!-- Einstellungen Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 md:ml-20">
                <x-dropdown align="right" width="60">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profil') }}
                        </x-dropdown-link>

                        <!-- Abmeldung -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                                {{ __('Abmelden') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menü -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigationsmenü -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->hasPermission('view_dashboard'))
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasPermission('view_customers'))
                <x-responsive-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.index')">
                    {{ __('Kunden') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasPermission('view_offers'))
                <x-responsive-nav-link :href="route('offer.index')" :active="request()->routeIs('offer.index')">
                    {{ __('Angebote') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasPermission('view_invoices'))
                <x-responsive-nav-link :href="route('invoice.index')" :active="request()->routeIs('invoice.index')">
                    {{ __('Rechnungen') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasPermission('view_sales_analysis'))
                <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                    {{ __('Umsatz') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasPermission('manage_users'))
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    {{ __('Benutzer') }}
                </x-responsive-nav-link>
            @endif



            <!-- Rollenverwaltung mit Untermenü in mobiler Ansicht -->
            @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_permissions') || auth()->user()->hasPermission('view_clients') || auth()->user()->hasPermission('manage_users') || auth()->user()->hasPermission('view_conditions') || auth()->user()->hasPermission('edit_my_client_settings') || auth()->user()->hasPermission('view_client_versions') || auth()->user()->hasPermission('manage_maintenance') || auth()->user()->hasPermission('manage_currencies'))
                <div x-data="{ openSubmenu: false }" class="space-y-1">
                    <x-responsive-nav-link href="#" @click.prevent="openSubmenu = !openSubmenu" class="flex items-center">
                        <span>{{ __('App-Einstellungen') }}</span>
                        <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <!-- SVG path -->
                        </svg>
                    </x-responsive-nav-link>
                    <div x-show="openSubmenu" class="space-y-1 mt-2">
                        @if(auth()->user()->hasPermission('manage_roles'))
                            <x-responsive-nav-link :href="route('roles.index')">
                                {{ __('Rollen') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->hasPermission('manage_permissions'))
                            <x-responsive-nav-link :href="route('permissions.index')">
                                {{ __('Rechte') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->hasPermission('view_clients'))
                            <x-responsive-nav-link :href="route('clients.index')">
                                {{ __('Klienten') }}
                            </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->hasPermission('manage_currencies'))
                            <x-responsive-nav-link :href="route('currencies.index')">
                                {{ __('Währungen') }}
                            </x-responsive-nav-link>
                        @endif

                        <!-- Client-Einstellungen in mobiler Ansicht -->
                        @if(auth()->user()->hasPermission('edit_my_client_settings'))
                            <div class="border-t border-gray-300 mx-4 my-2"></div>
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500">Client-Einstellungen</div>

                            <x-responsive-nav-link :href="route('clients.my-settings')" :active="request()->routeIs('clients.my-settings')">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ __('Firmendaten') }}
                                </div>
                            </x-responsive-nav-link>

                            @php
                                $client = App\Models\Clients::active()->where('id', auth()->user()->client_id)->first();
                            @endphp
                            @if($client && auth()->user()->hasPermission('view_client_versions'))
                                <x-responsive-nav-link :href="route('clients.versions', $client->id)" :active="request()->routeIs('clients.versions')">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ __('Versionshistorie') }}
                                    </div>
                                </x-responsive-nav-link>
                            @endif

                            <!-- Statische Einstellungen in mobiler Ansicht -->
                            @if(auth()->user()->hasPermission('edit_client_settings'))
                                <x-responsive-nav-link :href="route('client-settings.edit')" :active="request()->routeIs('client-settings.*')">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ __('Einstellungen') }}
                                    </div>
                                </x-responsive-nav-link>
                            @endif
                        @endif

                        <!-- Wartungsmodus in mobiler Ansicht -->
                        @if(auth()->user()->hasPermission('manage_maintenance'))
                            <div class="border-t border-gray-300 mx-4 my-2"></div>
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500">System</div>
                            
                            <x-responsive-nav-link :href="route('maintenance.index')" :active="request()->routeIs('maintenance.*')">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ __('Wartungsmodus') }}
                                </div>
                            </x-responsive-nav-link>
                        @endif

                        <!-- Weitere Untermenüpunkte -->
                    </div>
                </div>
            @endif
            <!-- Ende des Untermenüs -->
        </div>

        <!-- Responsive Einstellungen -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil') }}
                </x-responsive-nav-link>

                <!-- Abmeldung -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Abmelden') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
