<!--
  Navbar: dunkler Hintergrund, links Logo + Navigation,
  rechts Profil-Icon mit Dropdown.
  Mobile: Hamburger-Button anstatt Desktop-Navigation
  Alpine.js: toggelt 'mobileOpen' (Mobile-Menü) und 'profileOpen' (Profil-Dropdown)
-->
<nav x-data="{ mobileOpen: false, profileOpen: false }" class="bg-gray-800">
    <!-- Haupt-Container: max. Breite + Innenabstände -->
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
      <div class="relative flex h-16 items-center justify-between">

        <!-- ===================================
             MOBILE: Hamburger-Button (links)
             Wird nur auf kleinen Screens angezeigt
             (sm:hidden => ab 640px wird es ausgeblendet)
             =================================== -->
        <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
          <button
            type="button"
            @click="mobileOpen = !mobileOpen"
            class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400
                   hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2
                   focus:ring-inset focus:ring-white"
            aria-controls="mobile-menu"
            aria-expanded="false"
          >
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>

            <!-- Icon, wenn Menü geschlossen ist -->
            <svg
              x-show="!mobileOpen"
              class="block size-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
              data-slot="icon"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
              />
            </svg>
            <!-- Icon, wenn Menü geöffnet ist -->
            <svg
              x-show="mobileOpen"
              class="hidden size-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke-width="1.5"
              stroke="currentColor"
              aria-hidden="true"
              data-slot="icon"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>

        <!-- ===================================
             LINKER TEIL: Logo + Desktop-Navigation
             Flex-Container: Logo & Navlinks
             =================================== -->
        <div class="flex flex-1 items-center justify-center sm:items-stretch sm:justify-start">
          <!-- Logo -->
          <div class="flex shrink-0 items-center">
            <x-application-logo class="block h-9 w-auto fill-current" />
          </div>

          <!-- Desktop-Menü
               hidden sm:block => auf Mobil versteckt, ab 640px (sm) sichtbar
          -->
          <div class="hidden sm:ml-6 sm:block">
            <div class="flex space-x-4">
              <!-- Beispiel-Links: Dashboard, Team, usw. -->
              <!-- Aktiver Link: bg-gray-900 text-white -->
              <!-- Inaktiv: text-gray-300 hover:bg-gray-700 hover:text-white -->
              <a href="{{route('dashboard')}}" class="rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white">
                Dashboard
              </a>
              <a href="{{route('customer.index')}}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white" aria-current="page">
                Kunden
              </a>
              <a
                href="#"
                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300
                       hover:bg-gray-700 hover:text-white"
              >
                Projects
              </a>
              <a
                href="#"
                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300
                       hover:bg-gray-700 hover:text-white"
              >
                Calendar
              </a>
            </div>
          </div>
        </div>

        <!-- ===================================
             RECHTER TEIL: User-Icon + Dropdown
             (Notifications entfernt)
             =================================== -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-2
                    sm:static sm:inset-auto sm:ml-6 sm:pr-0">

          <!-- Profil-Dropdown -->
          <div class="relative ml-3">
            <div>
              <!-- Button mit Personen-Icon -->
              <button
                type="button"
                @click="profileOpen = !profileOpen"
                class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none
                       focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                id="user-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
              >
                <span class="absolute -inset-1.5"></span>
                <span class="sr-only">Open user menu</span>
                <!-- Personen-Icon (statt Avatar) -->
                <svg
                  class="size-8 text-gray-300"
                  fill="none"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="1.5"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <!-- Beispiel: Heroicon "User" -->
                  <path
                    d="M15.75 9A3.75 3.75 0 1112 5.25
                       3.75 3.75 0 0115.75 9zM4.5
                       19.5a8.25 8.25 0 0115 0"
                  />
                </svg>
              </button>
            </div>

            <!-- Dropdown-Menü (auf Desktop angezeigt, wenn profileOpen = true) -->
            <div
              x-show="profileOpen"
              @click.away="profileOpen = false"
              x-transition:enter="transition ease-out duration-100"
              x-transition:enter-start="transform opacity-0 scale-95"
              x-transition:enter-end="transform opacity-100 scale-100"
              x-transition:leave="transition ease-in duration-75"
              x-transition:leave-start="transform opacity-100 scale-100"
              x-transition:leave-end="transform opacity-0 scale-95"
              class="absolute right-0 z-10 mt-2 w-48 origin-top-right
                     rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5
                     focus:outline-none"
              role="menu"
              aria-orientation="vertical"
              aria-labelledby="user-menu-button"
              tabindex="-1"
              style="display: none;" <!-- verhindert kurzes Einblitzen -->
            >
              <!-- Dropdown-Einträge -->
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700"
                role="menuitem"
                tabindex="-1"
              >
                Your Profile
              </a>
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700"
                role="menuitem"
                tabindex="-1"
              >
                Settings
              </a>
              <a
                href="#"
                class="block px-4 py-2 text-sm text-gray-700"
                role="menuitem"
                tabindex="-1"
              >
                Sign out
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ===================================
         MOBILES MENÜ (wenn Hamburger geklickt)
         sm:hidden => Ab 640px wird dieses Menü nicht angezeigt
         =================================== -->
    <div
      class="sm:hidden"
      id="mobile-menu"
      x-show="mobileOpen"
      x-transition
      style="display: none;"
    >
      <div class="space-y-1 px-2 pb-3 pt-2">
        <!-- Entspricht der Desktop-Navigation, nur mobil -->
        <a
          href="#"
          class="block rounded-md bg-gray-900 px-3 py-2 text-base font-medium text-white"
          aria-current="page"
        >
          Dashboard
        </a>
        <a
          href="#"
          class="block rounded-md px-3 py-2 text-base font-medium text-gray-300
                 hover:bg-gray-700 hover:text-white"
        >
          Team
        </a>
        <a
          href="#"
          class="block rounded-md px-3 py-2 text-base font-medium text-gray-300
                 hover:bg-gray-700 hover:text-white"
        >
          Projects
        </a>
        <a
          href="#"
          class="block rounded-md px-3 py-2 text-base font-medium text-gray-300
                 hover:bg-gray-700 hover:text-white"
        >
          Calendar
        </a>
      </div>
    </div>
  </nav>
