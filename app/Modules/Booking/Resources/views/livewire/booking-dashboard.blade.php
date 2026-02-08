<x-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Booking-Modul Übersicht</h1>
        <p class="text-gray-600 mt-1">Verwalten Sie alle Funktionen des Booking-Moduls</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Zimmer gesamt</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_rooms'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['active_rooms'] }} aktiv</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Reservierungen</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_reservations'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['pending_reservations'] }} ausstehend</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Heute Check-ins</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['today_checkins'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['today_checkouts'] }} Check-outs</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Anstehende</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['upcoming_reservations'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['confirmed_reservations'] }} bestätigt</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Schnellzugriff</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.booking.reservations.index') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="font-medium">Reservierungen</span>
            </a>

            <a href="{{ route('booking.rooms.index') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="font-medium">Zimmer anzeigen</span>
            </a>

            <a href="{{ route('booking.reserve') }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="font-medium">Neue Reservierung</span>
            </a>

            <a href="{{ route('admin.booking.rooms.availability', 1) }}" 
               class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="font-medium">Verfügbarkeit</span>
            </a>
        </div>
    </div>

    {{-- Recent Reservations --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Neueste Reservierungen</h2>
            </div>
            <div class="p-6">
                @if($recentReservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentReservations as $reservation)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reservation->reservation_number }}</p>
                                    <p class="text-sm text-gray-600">{{ $reservation->guest_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $reservation->room->name }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($reservation->status === 'paid') bg-blue-100 text-blue-800
                                        @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                    <p class="text-sm text-gray-600 mt-1">{{ number_format($reservation->total_amount, 2, ',', '.') }} €</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Noch keine Reservierungen</p>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Anstehende Reservierungen</h2>
            </div>
            <div class="p-6">
                @if($upcomingReservations->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingReservations as $reservation)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $reservation->guest_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $reservation->room->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $reservation->check_in->format('d.m.Y') }} - {{ $reservation->check_out->format('d.m.Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($reservation->status === 'paid') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">Keine anstehenden Reservierungen</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Module Information --}}
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Booking-Modul Funktionen</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
            <div>
                <h4 class="font-semibold mb-2">Zimmerverwaltung</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Zimmer erstellen und verwalten</li>
                    <li>Preise und Ausstattung festlegen</li>
                    <li>Verfügbarkeitskalender</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Reservierungen</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Reservierungen verwalten</li>
                    <li>Status-Flow: pending → paid → confirmed</li>
                    <li>Automatische Reservierungsnummern</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Zahlungen</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Online-Zahlungen (keine Barzahlung)</li>
                    <li>Payment-Provider Integration</li>
                    <li>Zahlungsverfolgung</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-2">Integration</h4>
                <ul class="list-disc list-inside space-y-1">
                    <li>Automatische Rechnungserstellung</li>
                    <li>Kundenerstellung aus Reservierungen</li>
                    <li>Service-Layer Integration</li>
                </ul>
            </div>
        </div>
    </div>
</x-layout>
