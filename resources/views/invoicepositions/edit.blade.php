<x-layout>
    <div class="container">
        <div class="row">
            <div class="col text-left">
                <h3>Position bearbeiten</h3>
            </div>
            <div class="col text-right">
                <form action="{{ route('invoice.edit', ['invoice' => $invoicepositioncontent->invoice_id]) }}" method="GET">
                    <button type="submit" class="btn btn-transparent">Zurück</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">

                @if ($invoicepositioncontent->positiontextoption == 0)
                    <form method="POST" action="{{ route('invoiceposition.update', ['invoiceposition' => $invoicepositioncontent->id])}}" class="p-3 mb-3" id="normalForm">
                        @csrf
                        @method('PUT') <!-- Wenn du die Position aktualisieren möchtest -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="amount">Menge</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount', $invoicepositioncontent->amount) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="unit_id">Einheit</label>
                                <select class="form-control" id="unit_id" name="unit_id">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $unit->id == old('unit_id', $invoicepositioncontent->unit_id) ? 'selected' : '' }}>
                                            {{ $unit->unitdesignation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="designation">Beschreibung</label>
                                <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation', $invoicepositioncontent->designation) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="price">Preis/EH</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $invoicepositioncontent->price) }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="sequence">Reihenfolge</label>
                                <input type="text" class="form-control" id="sequence" name="sequence" value="{{ old('sequence', $invoicepositioncontent->sequence) }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-10">
                                <label for="details">Positionsdetail</label>
                                <textarea class="form-control" id="details" name="details" rows="10">{{ old('details', $invoicepositioncontent->details) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{$invoicepositioncontent->id}}">
                            <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                        </div>
                    </form>
                @else
                    <form method="POST" action="{{ route('invoiceposition.update', ['invoiceposition' => $invoicepositioncontent->id])}}" class="p-3 mb-3" id="normalForm">
                        @csrf
                        @method('PUT') <!-- Füge diese Zeile hinzu -->


                        <div class="form-group col-md-3">
                            <label for="sequence">Reihenfolge</label>
                            <input type="text" class="form-control" id="sequence" name="sequence" value="{{$invoicepositioncontent->sequence}}">
                        </div>

                        <div class="form-group col-md-10" id="onlyComment">
                            <label for="positiontext">Positionstext</label>
                            <input type="hidden" id="amount" name="amount" value="0">
                            <input type="hidden" id="unit_id" name="unit_id" value="1">
                            <input type="hidden" id="designation" name="designation" value="1">
                            <input type="hidden" id="price" name="price" value="0">
                            <textarea class="form-control" name="details" rows="10">{{$invoicepositioncontent->details}}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{$invoicepositioncontent->id}}">
                            <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layout>
