<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div>
            <h1 class="text-base font-semibold text-gray-900">Bedingung bearbeiten</h1>
            <p class="mt-2 text-sm text-gray-700">Bearbeite die Bedingung nach Bedarf.</p>
        </div>



        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Fehler!</strong> Bitte behebe die folgenden Probleme:<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('condition.update', $condition->id) }}" method="POST" class="sm:col-span-1 md:col-span-5">
            @csrf
            @method('PUT')

            <div class="grid  grid-cols-1 gap-x-6 gap-y-8 md:grid-cols-4 sm:grid-cols-1 md:col-span-1">
                <div>
                    <x-input name="conditionname" type="text" placeholder="Name" label="Name" value="{{ old('conditionname', $condition->conditionname) }}" required />
                </div>

                <!-- Client wird automatisch basierend auf dem eingeloggten User gesetzt -->
                <div class="mb-4 mt-4 sm:col-span-1 p-3 bg-gray-50 rounded-md">
                    <div class="text-sm font-medium text-gray-700">Client</div>
                    <div class="text-sm text-gray-900">{{ $condition->client->clientname ?? 'Unbekannt' }}</div>
                    <div class="text-xs text-gray-500 mt-1">Der Client kann nicht geändert werden.</div>
                </div>
            </div>

            <div class="flex items-center">
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">Aktualisieren</button>
                <a href="{{ route('condition.index') }}" class="ml-4 rounded-md bg-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-200">Zurück</a>
            </div>
        </form>
    </div>


</x-layout>
