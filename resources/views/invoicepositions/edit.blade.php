<x-layout>
    <div class="container p-3">
        <div class="row">
            <div class="col">
                <h3>Position bearbeiten</h3>
            </div>
            <div class="col col-auto d-flex align-items-center">
                <a href="{{ route('invoice.edit', ['invoice' => $position->invoice_id]) }}" class="btn btn-transparent">Zurück</a>
            </div>
        </div>

        <div class="container">
            <form method="POST" action="{{ route('invoiceposition.update', $position->id) }}">
                @csrf
                @method('PUT') <!-- Wenn du die Position aktualisieren möchtest -->

                <div class="form-row mt-4">
                    <div class="form-group col-md-6">
                        <label for="amount">Menge</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount', $position->amount) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="unit_id">Einheit</label>
                        <select class="form-control" id="unit_id" name="unit_id">
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ $unit->id == old('unit_id', $position->unit_id) ? 'selected' : '' }}>
                                    {{ $unit->unitdesignation }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="designation">Beschreibung</label>
                        <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation', $position->designation) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="price">Preis/EH</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $position->price) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="sequence">Reihenfolge</label>
                        <input type="text" class="form-control" id="sequence" name="sequence" value="{{ old('sequence', $position->sequence) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-10">
                        <label for="details">Positionsdetail</label>
                        <textarea class="form-control" id="details" name="details" rows="10">{{ old('details', $position->details) }}</textarea>
                    </div>
                </div>

                @if ($position->PositionTextOption == 0)
                    <div class="form-group col-md-10">
                        <label for="details">Positionstext</label>
                        <textarea class="form-control" id="details" name="details" rows="10">{{ old('details', $position->positiontext) }}</textarea>
                    </div>
                @endif

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
