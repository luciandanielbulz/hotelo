<div x-data="{ openCustomerModal: false }">
    @if(session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition:leave="transition-opacity ease-linear duration-300" class="rounded-lg bg-green-50 p-4 mb-6 border border-green-200">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="size-5 text-green-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.236 4.53L8.23 10.661a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Erfolgreich!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            @if($customer)
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                    {{ substr($customer->companyname ?: $customer->customername, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $customer->companyname ?: $customer->customername }}
                    </h3>
                    <p class="text-sm text-gray-600">Kundennummer: {{ $customer->customer_number ?: $customer->id }}</p>
                </div>
            @else
                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-gray-600 font-bold text-lg">
                    ?
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Kein Kunde zugewiesen</h3>
                    <p class="text-sm text-gray-600">Bitte wählen Sie einen Kunden aus</p>
                </div>
            @endif
        </div>
        
        <button @click="openCustomerModal = true" 
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-medium rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            Kunde wechseln
        </button>
    </div>

    @if($customer)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                @if($customer->customername)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="text-gray-700">{{ $customer->customername }}</span>
                    </div>
                @endif
                
                @if($customer->email)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700">{{ $customer->email }}</span>
                    </div>
                @endif

                @if($customer->phone)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-700">{{ $customer->phone }}</span>
                    </div>
                @endif
            </div>
            
            <div class="space-y-2">
                @if($customer->address)
                    <div class="flex items-start text-sm">
                        <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div class="text-gray-700">
                            <div>{{ $customer->address }}</div>
                            @if($customer->postalcode || $customer->location)
                                <div>{{ $customer->postalcode }} {{ $customer->location }}</div>
                            @endif
                            @if($customer->country)
                                <div>{{ $customer->country }}</div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Kunden-Auswahl Modal - TELEPORTIERT AN BODY -->
    <div x-show="openCustomerModal" @customer-updated.window="openCustomerModal = false"
         x-init="$watch('openCustomerModal', value => {
             if (value) {
                 document.body.appendChild($el);
                 document.body.style.overflow = 'hidden';
             } else {
                 document.body.style.overflow = 'auto';
             }
         })"
         class="fixed bg-gray-900 bg-opacity-80" 
         style="display: none; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999;" x-cloak>
        <div class="bg-white rounded-lg shadow-xl flex flex-col"
             style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 800px; height: 450px; max-height: 80vh; z-index: 1000000;"
             @click.away="openCustomerModal = false">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Kunden auswählen
                    </h2>
                    <button @click="openCustomerModal = false" 
                            class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full p-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Content -->
            <div class="flex-1 p-6 overflow-hidden">
                <div class="h-full overflow-y-auto">
                    <livewire:customer.search-list :offerId="$offerId" />
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end">
                    <button @click="openCustomerModal = false" 
                            class="px-6 py-2 bg-gray-500 text-white font-medium rounded-lg hover:bg-gray-600 transition-colors duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Schließen
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>