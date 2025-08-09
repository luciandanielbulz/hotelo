<!-- resources/views/components/sidebar.blade.php -->
<div x-data="{ openSubmenu: false, salesSubmenu: false }" class="flex grow flex-col gap-y-5 overflow-y-auto bg-indigo-600 px-6">
    <!-- Logo -->
    <div class="flex h-16 shrink-0 items-center">
        <a href="{{ route('dashboard') }}">
            <!-- Ersetze das Bild-Tag durch dein eigenes Logo -->
            <x-application-icon class="h-8 w-auto text-white" />
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                            Dashboard
                            <!-- Optional Badge -->
                            <span class="ml-auto w-9 min-w-max whitespace-nowrap rounded-full bg-indigo-600 px-2.5 py-0.5 text-center text-xs font-medium text-white ring-1 ring-inset ring-indigo-500" aria-hidden="true">5</span>
                        </a>
                    </li>

                    <!-- Dynamische Menüpunkte basierend auf Berechtigungen -->
                    @if($user && $user->hasPermission('view_customers'))
                        <li>
                            <a href="{{ route('customer.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.5 9.5 0 0 1-9.5-9.5m0 0a9.5 9.5 0 0 1 9.5-9.5m0 0a9.5 9.5 0 0 1 9.5 9.5m-9.5-9.5v9.5" />
                                </svg>
                                Kunden
                            </a>
                        </li>
                    @endif



                    @if($user && $user->hasPermission('view_offers'))
                        <!-- Angebote Dropdown -->
                        <li>
                            <button @click="openSubmenu = !openSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                Angebote
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': openSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="openSubmenu" @click.away="openSubmenu = false" class="mt-2 space-y-1 pl-5">
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
                        <li>
                            <a href="{{ route('invoice.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                Rechnungen
                            </a>
                        </li>
                    @endif

                    @if($user && $user->hasPermission('view_sales_analysis'))
                        <!-- Umsatzauswertung Dropdown -->
                        <li>
                            <button @click="salesSubmenu = !salesSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7 7" />
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                Postausgang
                            </a>
                        </li>
                    @endif

                    <!-- App-Einstellungen Dropdown -->
                    @if($user && ($user->hasPermission('manage_roles') || $user->hasPermission('manage_permissions') || $user->hasPermission('view_clients') || $user->hasPermission('edit_my_client_settings')))
                        <li>
                            <button @click="openSubmenu = !openSubmenu" class="group flex w-full items-center gap-x-3 rounded-md p-2 text-sm font-semibold text-indigo-200 hover:bg-indigo-700 hover:text-white focus:outline-none">
                                <!-- SVG Icon -->
                                <svg class="h-6 w-6 shrink-0 text-indigo-200 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                                App-Einstellungen
                                <!-- Dropdown Indicator -->
                                <svg :class="{ 'transform rotate-90': openSubmenu }" class="ml-auto h-5 w-5 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <!-- Dropdown Menü -->
                            <div x-show="openSubmenu" @click.away="openSubmenu = false" class="mt-2 space-y-1 pl-5">
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
                <a href="#" class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                    <img class="h-8 w-8 rounded-full bg-indigo-700" src="{{ Auth::user()->profile_photo_url }}" alt="">
                    <span class="sr-only">Your profile</span>
                    <span aria-hidden="true">{{ Auth::user()->name }}</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
