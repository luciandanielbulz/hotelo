@php
$candidates = [
    'logo/VenditioLogo.png',
    'VenditioLogo.png',
    'logo/VenditioLogoOhne.png',
];
$logoPath = null;
foreach ($candidates as $c) {
    if (file_exists(public_path($c))) { 
        $logoPath = asset($c); 
        break; 
    }
}
@endphp

<nav x-data="{ open: false, offersOpen: false, invoicesOpen: false, adminOpen: false }" 
     class="sticky top-0 z-50 bg-neutral-primary border-b border-default shadow-sm">
    
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1 group">
                        @if($logoPath)
                            <img src="{{ $logoPath }}" alt="Venditio" class="inline-block h-11 w-auto" />
                        @else
                            <x-application-icon class="inline-block h-9 w-auto fill-current text-heading" />
                        @endif
                        
                    </a>
                </div>

                <!-- Navigation Links (ab lg sichtbar; Tablet nutzt Burger) -->
                <div class="hidden space-x-6 lg:-my-px lg:ms-10 lg:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('dashboard') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                        Dashboard
                    </a>

                    @if(auth()->user()->hasPermission('view_customers'))
                    <a href="{{ route('customer.index', ['view' => 'cards']) }}" 
                       class="inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('customer.*') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                        Kunden
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_offers'))
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="group inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('offer.*') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                            <span>Angebote</span>
                            <svg class="ms-1 h-4 w-4 relative top-[1px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-cloak
                             @click.away="open = false"
                             class="absolute top-full left-0 mt-2 w-48 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg">
                            <div class="p-2">
                                <a href="{{ route('offer.index', ['view' => 'cards']) }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Alle Angebote</a>
                                <a href="{{ route('offer.index_archivated', ['view' => 'cards']) }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Archivierte</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->hasPermission('view_invoices'))
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="group inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('invoice.*') || request()->routeIs('invoiceupload.*') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                            <span>Rechnungen</span>
                            <svg class="ms-1 h-4 w-4 relative top-[1px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-cloak
                             @click.away="open = false"
                             class="absolute top-full left-0 mt-2 w-48 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg">
                            <div class="p-2">
                                <a href="{{ route('invoice.index', ['view' => 'cards']) }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Alle Rechnungen</a>
                                <a href="{{ route('invoice.index_archivated', ['view' => 'cards']) }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Archivierte</a>
                                <a href="{{ route('invoiceupload.index') }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Ausgabenverwaltung</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->hasPermission('view_sales_analysis'))
                    <a href="{{ route('sales.index') }}" 
                       class="inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('sales.*') || request()->routeIs('bankdata.*') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                        Umsatzauswertung
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('view_messages'))
                    <a href="{{ route('outgoingemails.index') }}" 
                       class="inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('outgoingemails.*') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                        Postausgang
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_permissions') || auth()->user()->hasPermission('view_clients') || auth()->user()->hasPermission('manage_users'))
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" 
                                class="group inline-flex items-center px-1 pt-6 border-b-2 text-base font-medium leading-5 transition duration-150 ease-in-out {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('clients.*') || request()->routeIs('users.*') ? 'border-brand text-heading' : 'border-transparent text-body hover:text-heading hover:border-neutral-tertiary' }}">
                            <span>Administration</span>
                            <svg class="ms-1 h-4 w-4 relative top-[1px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-cloak
                             @click.away="open = false"
                             class="absolute top-full left-0 mt-2 w-56 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg">
                            <div class="p-2">
                                @if(auth()->user()->hasPermission('manage_roles'))
                                <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Rollen</a>
                                @endif
                                @if(auth()->user()->hasPermission('manage_permissions'))
                                <a href="{{ route('permissions.index') }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Rechte</a>
                                @endif
                                @if(auth()->user()->hasPermission('manage_users'))
                                <a href="{{ route('users.index') }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Benutzer</a>
                                @endif
                                @if(auth()->user()->hasPermission('view_clients'))
                                <a href="{{ route('clients.index') }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Klienten</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown (ab lg sichtbar) -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" 
                            class="inline-flex items-center px-3 py-2 border border-transparent text-base leading-5 font-medium rounded-base text-body bg-neutral-primary hover:text-heading focus:outline-none transition ease-in-out duration-150">
                        <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="open" 
                         x-cloak
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg">
                        <div class="p-2">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">Profil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-base">
                                    Abmelden
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger (bis lg sichtbar) -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = !open" 
                        class="inline-flex items-center justify-center p-3 rounded-base text-body hover:text-heading hover:bg-neutral-secondary-soft focus:outline-none focus:bg-neutral-secondary-soft focus:text-heading transition duration-150 ease-in-out">
                    <svg class="h-7 w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile overlay -->
    <div x-cloak 
         x-show="open" 
         x-transition.opacity 
         class="fixed inset-0 z-[100] bg-black/40 lg:hidden" 
         @click="open=false"></div>

    <!-- Responsive Navigation Menu (bis lg sichtbar) -->
    <div x-cloak 
         class="lg:hidden fixed inset-y-0 right-0 z-[110] w-80 sm:w-96 max-w-full bg-neutral-primary shadow-xl transform transition-transform duration-200 ease-out border-l border-default" 
         :class="open ? 'translate-x-0' : 'translate-x-full'">
        
        <!-- Offcanvas header -->
        <div class="px-4 py-3 border-b border-default flex items-center justify-between">
            <div class="flex items-center gap-2">
                @if($logoPath)
                    <img src="{{ $logoPath }}" alt="Venditio" class="h-11 w-auto" />
                @else
                    <x-application-icon class="h-6 w-auto fill-current text-heading" />
                @endif
                
            </div>
            <button @click="open=false" 
                    class="inline-flex items-center justify-center p-2 rounded-base text-body hover:text-heading hover:bg-neutral-secondary-soft focus:outline-none">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Offcanvas scrollable content -->
        <div class="h-[calc(100%-3.25rem)] overflow-y-auto">
            <div class="pt-2 pb-3 px-2 space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="block px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base {{ request()->routeIs('dashboard') ? 'bg-neutral-secondary-soft' : '' }}">
                    Dashboard
                </a>

                @if(auth()->user()->hasPermission('view_customers'))
                <a href="{{ route('customer.index', ['view' => 'cards']) }}" 
                   class="block px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base {{ request()->routeIs('customer.*') ? 'bg-neutral-secondary-soft' : '' }}">
                    Kunden
                </a>
                @endif

                @if(auth()->user()->hasPermission('view_offers'))
                <div class="mt-2 border-t border-default pt-2">
                    <button type="button"
                            @click="offersOpen = !offersOpen"
                            class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base"
                            :aria-expanded="offersOpen.toString()">
                        <span>Angebote</span>
                        <svg class="h-4 w-4 transform transition-transform" :class="offersOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="offersOpen" x-cloak class="mt-1 space-y-1">
                        <a href="{{ route('offer.index', ['view' => 'cards']) }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Alle Angebote</a>
                        <a href="{{ route('offer.index_archivated', ['view' => 'cards']) }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Archivierte</a>
                    </div>
                </div>
                @endif

                @if(auth()->user()->hasPermission('view_invoices'))
                <div class="mt-2 border-t border-default pt-2">
                    <button type="button"
                            @click="invoicesOpen = !invoicesOpen"
                            class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base"
                            :aria-expanded="invoicesOpen.toString()">
                        <span>Rechnungen</span>
                        <svg class="h-4 w-4 transform transition-transform" :class="invoicesOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="invoicesOpen" x-cloak class="mt-1 space-y-1">
                        <a href="{{ route('invoice.index', ['view' => 'cards']) }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Alle Rechnungen</a>
                        <a href="{{ route('invoice.index_archivated', ['view' => 'cards']) }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Archivierte</a>
                        <a href="{{ route('invoiceupload.index') }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Ausgabenverwaltung</a>
                    </div>
                </div>
                @endif

                @if(auth()->user()->hasPermission('view_sales_analysis'))
                <a href="{{ route('sales.index') }}" 
                   class="block px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base">
                    Umsatzauswertung
                </a>
                @endif

                @if(auth()->user()->hasPermission('view_messages'))
                <a href="{{ route('outgoingemails.index') }}" 
                   class="block px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base">
                    Postausgang
                </a>
                @endif

                @if(auth()->user()->hasPermission('manage_roles') || auth()->user()->hasPermission('manage_permissions') || auth()->user()->hasPermission('view_clients') || auth()->user()->hasPermission('manage_users'))
                <div class="mt-2 border-t border-default pt-2">
                    <button type="button"
                            @click="adminOpen = !adminOpen"
                            class="w-full flex items-center justify-between px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base"
                            :aria-expanded="adminOpen.toString()">
                        <span>Administration</span>
                        <svg class="h-4 w-4 transform transition-transform" :class="adminOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="adminOpen" x-cloak class="mt-1 space-y-1">
                        @if(auth()->user()->hasPermission('manage_roles'))
                        <a href="{{ route('roles.index') }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Rollen</a>
                        @endif
                        @if(auth()->user()->hasPermission('manage_permissions'))
                        <a href="{{ route('permissions.index') }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Rechte</a>
                        @endif
                        @if(auth()->user()->hasPermission('manage_users'))
                        <a href="{{ route('users.index') }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Benutzer</a>
                        @endif
                        @if(auth()->user()->hasPermission('view_clients'))
                        <a href="{{ route('clients.index') }}" class="block pl-10 py-2 text-base text-body hover:text-heading hover:bg-neutral-tertiary rounded-base">Klienten</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-default px-2">
                <div class="px-2">
                    <div class="font-medium text-base text-heading" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-body">{{ auth()->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.edit') }}" 
                       class="block px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base">
                        Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-base font-medium text-heading hover:bg-neutral-tertiary rounded-base">
                            Abmelden
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>
