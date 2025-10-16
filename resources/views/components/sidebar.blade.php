<!-- resources/views/components/sidebar.blade.php -->
<div x-data="{ offersSubmenu: {{ request()->routeIs('offer.*') ? 'true' : 'false' }}, salesSubmenu: {{ (request()->routeIs('sales.*') || request()->routeIs('bankdata.*')) ? 'true' : 'false' }}, invoicesSubmenu: {{ (request()->routeIs('invoice.*') || request()->routeIs('invoiceupload.*')) ? 'true' : 'false' }}, adminSubmenu: {{ (request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('clients.*') || request()->routeIs('users.*') || request()->routeIs('logos.*') || request()->routeIs('clients.my-settings')) ? 'true' : 'false' }}, profileSubmenu: {{ request()->routeIs('profile.*') ? 'true' : 'false' }} }" class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 border-r border-gray-200">
    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('logo/VenditioLogo.png') }}" alt="Venditio" class="h-12 w-auto" />
        </a>
    </div>

    <div class="border-t border-gray-200"></div>

    <!-- Navigation -->
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                            <!-- SVG Icon -->
                            <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('dashboard') ? 'text-gray-700' : 'text-gray-500 group-hover:text-gray-900' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <!-- Dynamische Menüpunkte basierend auf Berechtigungen -->
                    @if($user && $user->hasPermission('view_customers'))
                        <li>
                            <a href="{{ route('customer.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('customer.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 {{ request()->routeIs('customer.*') ? 'text-gray-700' : 'text-gray-500 group-hover:text-gray-900' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                Kunden
                            </a>
                        </li>
                    @endif



                    @if($user && $user->hasPermission('view_offers'))
                        <!-- Angebote Dropdown -->
                        <li>
                            <button @click="offersSubmenu = !offersSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ request()->routeIs('offer.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-gray-500 group-hover:text-gray-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Angebote
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': offersSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="offersSubmenu" @click.away="offersSubmenu = false" class="mt-2 space-y-1 pl-5" @if(!request()->routeIs('offer.*')) x-cloak @endif>
                                <a href="{{ route('offer.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('offer.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    Abgebote
                                </a>
                                <a href="{{ route('offer.index_archivated') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('offer.index_archivated') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    Archivierte Abgebote
                                </a>
                            </div>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_invoices'))
                        <!-- Rechnungen Dropdown -->
                        <li>
                            <button @click="invoicesSubmenu = !invoicesSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ (request()->routeIs('invoice.*') || request()->routeIs('invoiceupload.*')) ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-gray-500 group-hover:text-gray-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Rechnungen
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': invoicesSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="invoicesSubmenu" @click.away="invoicesSubmenu = false" class="mt-2 space-y-1 pl-5" @if(!(request()->routeIs('invoice.*') || request()->routeIs('invoiceupload.*'))) x-cloak @endif>
                                <a href="{{ route('invoice.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('invoice.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    Alle Rechnungen
                                </a>
                                <a href="{{ route('invoice.index_archivated') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('invoice.index_archivated') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    Archivierte
                                </a>
                                <a href="{{ route('invoiceupload.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('invoiceupload.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                    Ausgabenverwaltung
                                </a>
                            </div>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_sales_analysis'))
                        <!-- Umsatzauswertung Dropdown -->
                        <li>
                            <button @click="salesSubmenu = !salesSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ (request()->routeIs('sales.*') || request()->routeIs('bankdata.*')) ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 {{ (request()->routeIs('sales.*') || request()->routeIs('bankdata.*')) ? 'text-gray-700' : 'text-gray-500 group-hover:text-gray-900' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Umsatzauswertung
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': salesSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="salesSubmenu" @click.away="salesSubmenu = false" class="mt-2 space-y-1 pl-5" @if(!(request()->routeIs('sales.*') || request()->routeIs('bankdata.*'))) x-cloak @endif>
                                @if($user->hasPermission('view_sales_analysis'))
                                    <a href="{{ route('sales.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('sales.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Umsatz
                                    </a>
                                @endif
                                @if($user->hasPermission('view_sales_analysis'))
                                    <a href="{{ route('bankdata.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('bankdata.index') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Ausgabenliste
                                    </a>
                                    <a href="{{ route('bankdata.upload.form') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('bankdata.upload.form') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Bankdatenupload
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_messages'))
                        <li>
                            <a href="{{ route('outgoingemails.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-gray-500 group-hover:text-gray-900" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Postausgang
                            </a>
                        </li>
                    @endif

                    <!-- App-Einstellungen Dropdown -->
                    @if($user && ($user->hasPermission('manage_roles') || $user->hasPermission('manage_permissions') || $user->hasPermission('view_clients') || $user->hasPermission('edit_my_client_settings')))
                        <li>
                            <button @click="adminSubmenu = !adminSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold {{ (request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('clients.*') || request()->routeIs('users.*') || request()->routeIs('logos.*') || request()->routeIs('clients.my-settings')) ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }} focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 {{ (request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('clients.*') || request()->routeIs('users.*') || request()->routeIs('logos.*') || request()->routeIs('clients.my-settings')) ? 'text-gray-700' : 'text-gray-500 group-hover:text-gray-900' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                App-Einstellungen
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': adminSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="adminSubmenu" @click.away="adminSubmenu = false" class="mt-2 space-y-1 pl-5" @if(!(request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('clients.*') || request()->routeIs('users.*') || request()->routeIs('logos.*') || request()->routeIs('clients.my-settings'))) x-cloak @endif>
                                @if($user->hasPermission('manage_roles'))
                                    <a href="{{ route('roles.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('roles.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Rollen
                                    </a>
                                @endif
                                @if($user->hasPermission('manage_permissions'))
                                    <a href="{{ route('permissions.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('permissions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Rechte
                                    </a>
                                @endif
                                @if($user->hasPermission('view_clients'))
                                    <a href="{{ route('clients.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('clients.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Klienten
                                    </a>
                                @endif
                                @if($user->hasPermission('manage_users'))
                                    <a href="{{ route('users.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Benutzer
                                    </a>
                                @endif
                                @if($user->hasPermission('view_clients'))
                                    <a href="{{ route('logos.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('logos.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Logos
                                    </a>
                                @endif
                                @if($user->hasPermission('edit_my_client_settings'))
                                    <a href="{{ route('clients.my-settings') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium {{ request()->routeIs('clients.my-settings') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100 hover:text-gray-900' }}">
                                        Firmen-Einstellungen
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endif
                </ul>
            </li>

            <!-- Footer / Benutzerinformationen -->
            <li class="-mx-6 mt-auto border-t border-gray-200">
                <div class="flex items-center gap-x-2 px-6 py-3">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-x-4 flex-1 rounded-md hover:bg-gray-100 px-2 py-2">
                        <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                        </div>
                    </a>
                    <button @click="profileSubmenu = !profileSubmenu" class="p-2 rounded-md hover:bg-gray-100" aria-label="Profil-Menü umschalten">
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
                <div x-show="profileSubmenu" x-cloak class="px-6 pb-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-x-3 px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-gray-100">
                        <svg class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left flex items-center gap-x-3 px-3 py-2 rounded-md text-sm text-red-600 hover:bg-red-50">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Abmelden
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>
</div>
