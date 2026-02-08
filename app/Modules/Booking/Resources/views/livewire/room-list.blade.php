<div>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Unsere Zimmer</h1>

        {{-- Search and Filter --}}
        <div class="mb-6 flex gap-4">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Zimmer suchen..." 
                class="flex-1 border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <label class="flex items-center gap-2">
                <input 
                    type="checkbox" 
                    wire:model.live="showActiveOnly"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <span>Nur aktive Zimmer</span>
            </label>
        </div>

        {{-- Rooms Grid --}}
        @if($rooms->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($rooms as $room)
                    <div class="border border-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow">
                        @if($room->image)
                            <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <h3 class="text-xl font-semibold mb-2">{{ $room->name }}</h3>
                            @if($room->description)
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $room->description }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-2xl font-bold text-blue-600">
                                    {{ number_format($room->price_per_night, 2, ',', '.') }} €
                                </span>
                                <span class="text-sm text-gray-500">/ Nacht</span>
                            </div>

                            <div class="flex items-center text-gray-600 mb-3">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Bis zu {{ $room->max_guests }} Personen</span>
                            </div>

                            @if($room->features && count($room->features) > 0)
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @foreach(array_slice($room->features, 0, 3) as $feature)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $feature }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <a 
                                href="{{ route('booking.reserve', $room->id) }}" 
                                class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors"
                            >
                                Jetzt reservieren
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $rooms->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Zimmer gefunden</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search)
                        Keine Zimmer gefunden für "{{ $search }}"
                    @else
                        Derzeit sind keine Zimmer verfügbar.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>
