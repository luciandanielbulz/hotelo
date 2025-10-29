<div>
    <div class="flex justify-end">
        <div class="w-full md:w-1/2 lg:w-1/2 xl:w-1/2 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg p-4 border border-gray-200 shadow-sm">
        <!-- Header -->
        <div class="flex items-center mb-3">
            <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <h2 class="text-sm font-medium text-gray-700">Berechnungen</h2>
        </div>
        
        <div class="space-y-3">
            <!-- Zwischensumme -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 border border-blue-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-700">Zwischensumme (Netto)</span>
                    </div>
                    <span class="text-base font-semibold text-blue-900">{{ number_format($total_price, 2, ',', '.') }} €</span>
                </div>
            </div>

            <!-- Umsatzsteuer -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-3 border border-green-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <span class="text-sm font-medium text-green-700">
                            zzgl. Umsatzsteuer ({{ $invoice->reverse_charge ? '0' : $tax_rate }} %)
                        </span>
                    </div>
                    <span class="text-base font-semibold text-green-900">
                        {{ number_format($invoice->reverse_charge ? 0 : $total_price * ($tax_rate / 100), 2, ',', '.') }} €
                    </span>
                </div>
            </div>

            <!-- Gesamtsumme -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-lg p-4 border-2 border-purple-300 shadow-md">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <span class="text-base font-bold text-purple-700">Gesamtsumme</span>
                    </div>
                    <span class="text-lg font-bold text-purple-900">
                        {{ number_format($invoice->reverse_charge ? $total_price : $total_price * (1 + ($tax_rate / 100)), 2, ',', '.') }} €
                    </span>
                </div>
            </div>

            @if ($depositAmount > 0)
                <!-- Anzahlung -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-lg p-3 border border-orange-200">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12l-1.411-1.411L16 13.177V8a1 1 0 00-1-1h-4.764a1 1 0 00-.894.553L6 12H4a1 1 0 00-1 1v6a1 1 0 001 1h2l3.342-4.447A1 1 0 0110.236 15H15a1 1 0 001-1v-2.823l2.589 2.588L20 12z"/>
                            </svg>
                            <span class="text-sm font-medium text-orange-700">Anzahlung</span>
                        </div>
                        <span class="text-base font-semibold text-orange-900">
                            {{ number_format($invoice->depositamount * -1, 2, ',', '.') }} €
                        </span>
                    </div>
                </div>

                <!-- Zu Zahlen -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-lg p-4 border-2 border-emerald-300 shadow-md">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-base font-bold text-emerald-700">Zu Zahlen</span>
                        </div>
                        <span class="text-lg font-bold text-emerald-900">
                            {{ number_format(($invoice->reverse_charge ? $total_price : $total_price * (1 + ($tax_rate / 100))) - $invoice->depositamount, 2, ',', '.') }} €
                        </span>
                    </div>
                </div>
            @endif

            <!-- Reverse Charge Hinweis -->
            @if($invoice->reverse_charge)
                <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-lg p-3 border border-amber-200">
                    <div class="flex items-start">
                        <svg class="w-4 h-4 mr-2 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-medium text-amber-700 mb-1">Reverse Charge Verfahren</p>
                            <p class="text-xs text-amber-600">
                                Umsatzsteuer wird gemäß § 13b UStG vom Leistungsempfänger geschuldet
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>