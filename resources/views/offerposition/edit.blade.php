<x-layout>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col text-left">
                <h2 class="text-base/7 font-semibold text-gray-900">Position bearbeiten</h2>
            </div>
            <div class="col text-right">
                <x-button route="{{ route('offer.edit', ['offer' => $offerpositioncontent->offer_id]) }}" value="Zurück" />
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if ($offerpositioncontent->positiontext == 0)
                    <form method="POST" action="{{ route('offerposition.update', ['offerposition' => $offerpositioncontent->id]) }}" class="p-3 mb-3">

                        @csrf
                        @method('PUT')
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-4">
                            <x-input type="number" step="0.01" name="amount" label="Menge" value="{{ old('amount', $offerpositioncontent->amount) }}" placeholder="Geben Sie die Menge ein" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900" />
                            <x-dropdown_body name="unit_id" label="Einheit" :options="$units->pluck('unitdesignation', 'id')" selected="{{ old('unit_id', $offerpositioncontent->unit_id) }}" class="block w-full appearance-none rounded-md bg-white px-3 py-1.5 text-base text-gray-900" />
                            <x-input type="number" step="0.01" name="price" label="Preis/EH" value="{{ old('price', $offerpositioncontent->price) }}" placeholder="Preis eingeben" />
                            <x-input name="sequence" label="Reihenfolge" value="{{ old('sequence', $offerpositioncontent->sequence) }}" placeholder="Reihenfolge eingeben" />
                        </div>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                            <x-input type="text" name="designation" label="Beschreibung" value="{{ old('designation', $offerpositioncontent->designation) }}" placeholder="Beschreibung eingeben" />
                        </div>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                            <label for="details" class="block text-sm/6 font-medium text-gray-900 mb-1">Positionsdetail</label>
                            <textarea name="details" id="details" rows="10" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">{{ old('details', $offerpositioncontent->details) }}</textarea>
                        </div>

                        <!-- Schaltflächen -->
                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                            <button type="submit" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Änderungen speichern</button>
                        </div>
                    </form>
                @else
                    <!-- Form für Positionstext -->
                    <form method="POST" action="{{ route('offerposition.update', ['offerposition' => $offerpositioncontent->id]) }}" class="p-3 mb-3">
                        @csrf
                        @method('PUT')
                        <x-input name="sequence" label="Reihenfolge" value="{{ $offerpositioncontent->sequence }}" placeholder="Reihenfolge eingeben" />
                        <div class="sm:col-span-3">
                            <label for="details" class="block text-sm/6 font-medium text-gray-900 mb-1">Positionstext</label>
                            <textarea name="details" rows="10" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>{{ $offerpositioncontent->details }}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{ $offerpositioncontent->id }}">
                            <input type="hidden" name="amount" value="{{ $offerpositioncontent->amount }}">
                            <input type="hidden" name="unit_id" value="{{ $offerpositioncontent->unit_id }}">
                            <input type="hidden" name="designation" value="{{ $offerpositioncontent->designation }}">
                            <input type="hidden" name="price" value="{{ $offerpositioncontent->price }}">
                            <button type="submit" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Änderungen speichern</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layout>
