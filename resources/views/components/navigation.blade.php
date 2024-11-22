<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Hauptnavigationsmenü -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigationslinks -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.index')">
                        {{ __('Kunden') }}
                    </x-nav-link>
                    <x-nav-link :href="route('offer.index')" :active="request()->routeIs('offer.index')">
                        {{ __('Angebote') }}
                    </x-nav-link>
                    <x-nav-link :href="route('invoice.index')" :active="request()->routeIs('invoice.index')">
                        {{ __('Rechnungen') }}
                    </x-nav-link>
                    <x-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                        {{ __('Umsatz') }}
                    </x-nav-link>
                    <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('Benutzer') }}
                    </x-nav-link>

                    <!-- Rollenverwaltung mit Untermenü -->
                    <div x-data="{ open: false }" class="relative sm:-my-px sm:flex">
                        <a href="#" @click.prevent="open = !open"
                           class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('roles.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                            <span>{{ __('App-Einstellungen') }}</span>
                            <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd" />
                            </svg>
                        </a>
                        <!-- Dropdown-Inhalt -->
                        <div x-show="open" @click.away="open = false" class="absolute z-50 mt-5 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                <x-dropdown-link :href="route('roles.index')">
                                    {{ __('Rollen') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('permissions.index')">
                                    {{ __('Rechte') }}
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('clients.index')">
                                    {{ __('Klienten') }}
                                </x-dropdown-link>
                                <!-- Weitere Untermenüpunkte -->
                            </div>
                        </div>
                    </div>
                    <!-- Ende des Untermenüs -->
                </div>
            </div>

            <!-- Einstellungen Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
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
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('customer.index')" :active="request()->routeIs('customer.index')">
                {{ __('Kunden') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('offer.index')" :active="request()->routeIs('offer.index')">
                {{ __('Angebote') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('invoice.index')" :active="request()->routeIs('invoice.index')">
                {{ __('Rechnungen') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('sales.index')" :active="request()->routeIs('sales.index')">
                {{ __('Umsatz') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                {{ __('Benutzer') }}
            </x-responsive-nav-link>

            <!-- Rollenverwaltung mit Untermenü in mobiler Ansicht -->
            <div x-data="{ openSubmenu: false }" class="space-y-1">
                <x-responsive-nav-link href="#" @click.prevent="openSubmenu = !openSubmenu" class="flex items-center">
                    <span>{{ __('Rollen und Rechte') }}</span>
                    <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.23 8.29a.75.75 0 01.02-1.06z"
                              clip-rule="evenodd" />
                    </svg>
                </x-responsive-nav-link>
                <div x-show="openSubmenu" class="space-y-1 mt-2">
                    <x-responsive-nav-link :href="route('roles.index')">
                        {{ __('Alle Rollen') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('roles.create')">
                        {{ __('Neue Rolle erstellen') }}
                    </x-responsive-nav-link>
                    <!-- Weitere Untermenüpunkte -->
                </div>
            </div>
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
