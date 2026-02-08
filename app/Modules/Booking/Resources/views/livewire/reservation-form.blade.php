<div>
    {{-- Reservation Form Livewire Component --}}
    <form wire:submit="submit">
        @if($room)
            <h2>{{ $room->name }}</h2>
        @endif

        <div class="mb-4">
            <label>Check-in</label>
            <input type="date" wire:model.live="checkIn" class="border rounded px-3 py-2">
            @error('checkIn') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label>Check-out</label>
            <input type="date" wire:model.live="checkOut" class="border rounded px-3 py-2">
            @error('checkOut') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        @if($isAvailable)
            <div class="text-green-500">Verfügbar</div>
            <div>Gesamt: {{ number_format($totalAmount, 2, ',', '.') }} € ({{ $nights }} Nächte)</div>
        @else
            <div class="text-red-500">Nicht verfügbar</div>
        @endif

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Reservieren</button>
    </form>
</div>
