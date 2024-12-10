<x-layout>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col text-left">
                <h2 class="text-base/7 font-semibold text-gray-900">Position bearbeiten</h4>
            </div>
            <div class="col text-right">
                <x-button route="{{ route('invoice.edit', ['invoice' => $invoicepositioncontent->invoice_id]) }}" value="Zurück" />
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if ($invoicepositioncontent->positiontext == 0)
                    <form method="POST" action="{{ route('invoiceposition.update', ['invoiceposition' => $invoicepositioncontent->id])}}" class="p-3 mb-3" id="normalForm">
                        @csrf
                        @method('PUT')
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-4">
                            <x-input type="number" step="0.01" name="amount" label="Menge" value="{{ old('amount', $invoicepositioncontent->amount) }}" placeholder="Geben Sie die Menge ein" />
                            <x-dropdown_body name="unit_id" label="Einheit" :options="$units->pluck('unitdesignation', 'id')" selected="{{ old('unit_id', $invoicepositioncontent->unit_id) }}" />
                            <x-input type="number" step="0.01" name="price" label="Preis/EH" value="{{ old('price', $invoicepositioncontent->price) }}" placeholder="Preis eingeben" />
                            <x-input name="sequence" label="Reihenfolge" value="{{ old('sequence', $invoicepositioncontent->sequence) }}" placeholder="Reihenfolge eingeben" />
                        </div>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                            <x-input type="text" name="designation" label="Beschreibung" value="{{ old('designation', $invoicepositioncontent->designation) }}" placeholder="Beschreibung eingeben" />
                        </div>

                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-1">
                            <label for="details" class="block text-sm/6 font-medium text-gray-900 mb-1">Positionsdetail</label>
                            <textarea name="details" id="details" rows="10" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">{{ old('details', $invoicepositioncontent->details) }}</textarea>
                        </div>

                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{ $invoicepositioncontent->id }}">

                            <button type="submit" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Änderungen speichern</button>
                        </div>
                    </form>
                @else
                    <form method="POST" action="{{ route('invoiceposition.update', ['invoiceposition' => $invoicepositioncontent->id])}}" class="p-3 mb-3" id="normalForm">
                        @csrf
                        @method('PUT')
                        <x-input name="sequence" label="Reihenfolge" value="{{ $invoicepositioncontent->sequence }}" placeholder="Reihenfolge eingeben" />
                        <div class="sm:col-span-3">
                            <label for="details" class="block text-sm font-medium text-gray-900">Positionstext</label>
                            <textarea name="details" rows="10" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600" required>{{ $invoicepositioncontent->details }}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{ $invoicepositioncontent->id }}">
                            <input type="hidden" name="unit_id" value="{{ $invoicepositioncontent->unit_id }}">
                            <input type="hidden" name="price" value="{{ $invoicepositioncontent->price }}">
                            <input type="hidden" name="designation" value="{{ $invoicepositioncontent->designation }}">
                            <input type="hidden" name="amount" value="{{ $invoicepositioncontent->amount }}">
                            <button type="submit" class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Änderungen speichern</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layout>
