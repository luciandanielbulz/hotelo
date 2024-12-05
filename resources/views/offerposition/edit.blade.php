<x-layout>
    <!-- Fehlermeldungen -->
    @if ($errors->any())
        <div class="px-4 py-2 mb-4 text-sm text-red-700 bg-red-100 rounded-md">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Erfolgsnachricht -->
    @if(session('success'))
        <div class="px-4 py-2 mb-4 text-sm text-green-700 bg-green-100 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
        <!-- Linke Spalte: Überschrift -->
        <div class="px-4 sm:px-0">
            <h4 class="text-base font-semibold text-gray-900">Position bearbeiten</h4>
        </div>
        <!-- Rechte Spalte: Zurück Button -->
        <div class="px-4 sm:px-0 text-right md:col-span-2">
            <form action="{{ route('offer.edit', ['offer' => $offerpositioncontent->offer_id]) }}" method="GET">
                <button type="submit" class="text-sm font-semibold text-gray-900">Zurück</button>
            </form>
        </div>

        <!-- Formular -->
        <div class="md:col-span-3">
            @if ($offerpositioncontent->positiontext == 0)
                <!-- Form für normale Position -->
                <form method="POST" action="{{ route('offerposition.update', ['offerposition' => $offerpositioncontent->id]) }}" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                    @csrf
                    @method('PUT')

                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Menge -->
                            <div class="sm:col-span-2">
                                <label for="amount" class="block text-sm font-medium text-gray-900">Menge</label>
                                <div class="mt-2">
                                    <input type="number" step="0.01" name="amount" id="amount" value="{{ $offerpositioncontent->amount }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                                    @error('amount')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Einheit -->
                            <div class="sm:col-span-2">
                                <label for="unit_id" class="block text-sm font-medium text-gray-900">Einheit</label>
                                <div class="mt-2">
                                    <select name="unit_id" id="unit_id" class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" {{ $unit->id == $offerpositioncontent->unit_id ? 'selected' : '' }}>
                                                {{ $unit->unitdesignation }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Preis/EH -->
                            <div class="sm:col-span-2">
                                <label for="price" class="block text-sm font-medium text-gray-900">Preis/EH</label>
                                <div class="mt-2">
                                    <input type="number" step="0.01" name="price" id="price" value="{{ $offerpositioncontent->price }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                                    @error('price')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Beschreibung -->
                            <div class="sm:col-span-6">
                                <label for="designation" class="block text-sm font-medium text-gray-900">Beschreibung</label>
                                <div class="mt-2">
                                    <input type="text" name="designation" id="designation" value="{{ $offerpositioncontent->designation }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                                    @error('designation')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Reihenfolge -->
                            <div class="sm:col-span-2">
                                <label for="sequence" class="block text-sm font-medium text-gray-900">Reihenfolge</label>
                                <div class="mt-2">
                                    <input type="text" name="sequence" id="sequence" value="{{ $offerpositioncontent->sequence }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                                    @error('sequence')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Positionsdetail -->
                            <div class="sm:col-span-6">
                                <label for="details" class="block text-sm font-medium text-gray-900">Positionsdetail</label>
                                <div class="mt-2">
                                    <textarea name="details" id="details" rows="10" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">{{ $offerpositioncontent->details }}</textarea>
                                    @error('details')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schaltflächen -->
                    <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                        <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                            Änderungen speichern
                        </button>
                    </div>
                </form>
            @else
                <!-- Form für Positionstext -->
                <form method="POST" action="{{ route('offerposition.update', ['offerposition' => $offerpositioncontent->id]) }}" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                    @csrf
                    @method('PUT')

                    <div class="px-4 py-6 sm:p-8">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <!-- Reihenfolge -->
                            <div class="sm:col-span-2">
                                <label for="sequence" class="block text-sm font-medium text-gray-900">Reihenfolge</label>
                                <div class="mt-2">
                                    <input type="text" name="sequence" id="sequence" value="{{ $offerpositioncontent->sequence }}" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">
                                    @error('sequence')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Positionstext -->
                            <div class="sm:col-span-6">
                                <label for="details" class="block text-sm font-medium text-gray-900">Positionstext</label>
                                <div class="mt-2">
                                    <input type="hidden" name="amount" value="0">
                                    <input type="hidden" name="unit_id" value="1">
                                    <input type="hidden" name="designation" value="1">
                                    <input type="hidden" name="price" value="0">
                                    <textarea name="details" id="details" rows="10" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">{{ $offerpositioncontent->details }}</textarea>
                                    @error('details')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schaltflächen -->
                    <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                        <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                            Änderungen speichern
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-layout>
