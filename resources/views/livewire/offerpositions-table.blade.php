<div>
    <!-- Formular zum Hinzufügen einer neuen Position -->
    <div class="row mt-3 pb-3">
        <div class="col p-3">
            <h4>Positionen</h4>
        </div>
        <div class="col col-auto">
            <button wire:click="addPosition" class="btn btn-transparent">+ Position</button>
            <button wire:click="addTextPosition"  class="btn btn-transparent">+ Textposition</button>
            <button id="editPosition"  class="btn btn-transparent" disabled>Bearbeiten</button>
            <button wire:click="deletePosition" id="deletePosition"  class="btn btn-transparent" disabled>Löschen</button>
        </div>

    </div>
    <div class ="row">
        <div class="col">

            <!-- Tabelle mit Positionen -->
            <table class= "table table-hover" >
                <thead>
                    <tr>
                        <th class = "table-header col-1">Pos</th>
                        <th class = "table-header col-1">Menge</th>
                        <th class = "table-header col-1">Einheit</th>
                        <th class = "table-header col-4">Beschreibung</th>
                        <th class = "table-header col-1">Preis/EH</th>
                        <th class = "table-header col-1">Gesampreis</th>
                    </tr>
                </thead>

                <tbody id="positionsTable">
                    @forelse ($positions as $position)
                        <tr data-id="{{ $position->id }}">
                            <td>{{ $position->id }}</td>
                            @if ($position->positiontext == 0)
                                <td>{{ number_format($position->amount, 2, ',', '.') }}</td>
                                <td>{{ $position->unit_name}}</td>
                                <td>{{ $position->designation }}</td>
                                <td>{{ number_format($position->price, 2, ',', '.') }} €</td>
                                <td>{{ number_format($position->price * $position->amount, 2, ',', '.') }} €</td>
                            @else
                                <td><b>P</b></td>
                                <td></td>
                                <td><b>{{ $position->details }}</b></td>
                                <td></td>
                                <td></td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Keine Kunden gefunden</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
