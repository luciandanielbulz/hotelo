<x-layout>
    <div class="container">
        <div class="row">
            <div class="col text-left">
                <h4>Position bearbeiten</h4>
            </div>
            <div class="col text-right">
                <form action="{{ route('offer.edit', ['offer' => $offerpositioncontent->offer_id]) }}" method="GET">
                    <button type="submit" class="btn btn-transparent">Zurück</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if ($offerpositioncontent->postiotiontext=1)
                    <form method="POST" action="{{route('offerposition.update', ['offerposition' => $offerpositioncontent->id])}}" class="p-3 mb-3" id="normalForm">
                        @csrf
                        @method('PUT') <!-- Füge diese Zeile hinzu -->
                        <div class="form-row ">
                            <div class="form-group col-md-3">
                                <label for="amount">Menge</label>
                                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{$offerpositioncontent->amount}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="unit_id">Einheit</label>
                                <select class="form-control" id="unit_id" name="unit_id">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ $unit->id == $offerpositioncontent->unit_id ? 'selected' : '' }}>
                                            {{ $unit->unitdesignation }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="designation">Beschreibung</label>
                                <input type="text" class="form-control" id="designation" name="designation" value="{{$offerpositioncontent->designation}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="price">Preis/EH</label>
                                <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{$offerpositioncontent->price}}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="sequence">Reihenfolge</label>
                                <input type="text" class="form-control" id="sequence" name="sequence" value="{{$offerpositioncontent->sequence}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-10">
                                <label for="details">Positionsdetail</label>
                                <textarea class="form-control" id="details" name="details" rows="10">{{$offerpositioncontent->details}}</textarea>
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <input type="hidden" name="id" value="{{$offerpositioncontent->id}}">
                            <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                        </div>
                    </form>
                @else
                    <form method="POST" action="includes/offer_position_update.inc.php" class="p-3 mb-3" id="textForm" style="display: <?php echo !$showNormalForm ? 'block' : 'none'; ?>;">
                        <div class="form-group col-md-10" id="onlyComment">
                            <label for="positiontext">Positionstext</label>
                            <textarea class="form-control" id="positiontext" name="positiontext" rows="10"><?php echo htmlspecialchars($positiontext); ?></textarea>
                        </div>
                        <div class="form-group mt-4">
                            <input type="hidden" name="positionid" value="<?php echo htmlspecialchars($positionId); ?>">
                            <button type="submit" class="btn btn-primary">Änderungen speichern</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>


</x-layout>
