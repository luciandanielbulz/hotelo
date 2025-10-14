<!-- resources/views/components/sidebar.blade.php -->
<div x-data="{ offersSubmenu: false, salesSubmenu: false, invoicesSubmenu: false, adminSubmenu: false }" class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6">
    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center">
        <a href="{{ route('dashboard') }}">
            <img src="{{ asset('logo/VenditioLogo.png') }}" alt="Venditio" class="h-12 w-auto" />
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <!-- Dashboard Link -->
                    <li>
                        <a href="{{ route('dashboard') }}" class="group flex gap-x-3 rounded-md bg-indigo-700 p-2 text-sm font-semibold text-white">
                            <!-- SVG Icon -->
                            <svg class="h-6 w-6 shrink-0 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            Dashboard
                        </a>
                    </li>

                    <!-- Dynamische Menüpunkte basierend auf Berechtigungen -->
                    @if($user && $user->hasPermission('view_customers'))
                        <li>
                            <a href="{{ route('customer.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                Kunden
                            </a>
                        </li>
                    @endif



                    @if($user && $user->hasPermission('view_offers'))
                        <!-- Angebote Dropdown -->
                        <li>
                            <button @click="offersSubmenu = !offersSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Angebote
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': offersSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="offersSubmenu" @click.away="offersSubmenu = false" class="mt-2 space-y-1 pl-5">
                                <a href="{{ route('offer.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                    Abgebote
                                </a>
                                <a href="{{ route('offer.index_archivated') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                    Archivierte Abgebote
                                </a>
                            </div>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_invoices'))
                        <!-- Rechnungen Dropdown -->
                        <li>
                            <button @click="invoicesSubmenu = !invoicesSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Rechnungen
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': invoicesSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="invoicesSubmenu" @click.away="invoicesSubmenu = false" class="mt-2 space-y-1 pl-5">
                                <a href="{{ route('invoice.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                    Alle Rechnungen
                                </a>
                                <a href="{{ route('invoice.index_archivated') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                    Archivierte
                                </a>
                                <a href="{{ route('invoiceupload.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                    Ausgabenverwaltung
                                </a>
                            </div>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_sales_analysis'))
                        <!-- Umsatzauswertung Dropdown -->
                        <li>
                            <button @click="salesSubmenu = !salesSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Umsatzauswertung
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': salesSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="salesSubmenu" @click.away="salesSubmenu = false" class="mt-2 space-y-1 pl-5">
                                @if($user->hasPermission('view_sales_analysis'))
                                    <a href="{{ route('sales.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Umsatz
                                    </a>
                                @endif
                                @if($user->hasPermission('view_sales_analysis'))
                                    <a href="{{ route('bankdata.upload.form') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Bankdatenupload
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_messages'))
                        <li>
                            <a href="{{ route('outgoingemails.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Postausgang
                            </a>
                        </li>
                    @endif

                    <!-- App-Einstellungen Dropdown -->
                    @if($user && ($user->hasPermission('manage_roles') || $user->hasPermission('manage_permissions') || $user->hasPermission('view_clients') || $user->hasPermission('edit_my_client_settings')))
                        <li>
                            <button @click="adminSubmenu = !adminSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
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
                            <div x-show="adminSubmenu" @click.away="adminSubmenu = false" class="mt-2 space-y-1 pl-5">
                                @if($user->hasPermission('manage_roles'))
                                    <a href="{{ route('roles.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Rollen
                                    </a>
                                @endif
                                @if($user->hasPermission('manage_permissions'))
                                    <a href="{{ route('permissions.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Rechte
                                    </a>
                                @endif
                                @if($user->hasPermission('view_clients'))
                                    <a href="{{ route('clients.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Klienten
                                    </a>
                                @endif
                                @if($user->hasPermission('manage_users'))
                                    <a href="{{ route('users.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Benutzer
                                    </a>
                                @endif
                                @if($user->hasPermission('view_clients'))
                                    <a href="{{ route('logos.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Logos
                                    </a>
                                @endif
                                @if($user->hasPermission('edit_my_client_settings'))
                                    <a href="{{ route('clients.my-settings') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-medium text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                        Firmen-Einstellungen
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endif
                </ul>
            </li>

            <!-- Footer / Benutzerinformationen -->
            <li class="-mx-6 mt-auto">
                <div class="flex items-center gap-x-4 px-6 py-3">
                    <div class="h-8 w-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-semibold text-white">{{ auth()->user()->name }}</span>
                        <span class="text-xs text-indigo-200">{{ auth()->user()->email }}</span>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</div>
