<x-layout>
    <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-5">
            <div class="px-4 sm:px-0">
                <h2 class="text-base font-semibold text-gray-900">Währung bearbeiten</h2>
                <p class="mt-1 text-sm text-gray-600">Bearbeiten Sie die Details der Währung {{ $currency->code }}.</p>
            </div>

            <form action="{{ route('currencies.update', $currency) }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="px-4 py-6 sm:p-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label for="code" class="block text-sm font-medium text-gray-900">Währungscode *</label>
                            <div class="mt-2">
                                <input type="text" name="code" id="code" value="{{ old('code', $currency->code) }}" 
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                                       placeholder="EUR, USD, CHF"
                                       maxlength="3"
                                       pattern="[A-Z]{3}"
                                       required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">3-stelliger ISO-Code (z.B. EUR, USD)</p>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm font-medium text-gray-900">Währungsname *</label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" value="{{ old('name', $currency->name) }}"
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                                       placeholder="Euro, US-Dollar, Schweizer Franken"
                                       required>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="symbol" class="block text-sm font-medium text-gray-900">Symbol *</label>
                            <div class="mt-2">
                                <input type="text" name="symbol" id="symbol" value="{{ old('symbol', $currency->symbol) }}"
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                                       placeholder="€, $, CHF"
                                       maxlength="10"
                                       required>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="exchange_rate" class="block text-sm font-medium text-gray-900">Wechselkurs *</label>
                            <div class="mt-2">
                                <input type="number" name="exchange_rate" id="exchange_rate" value="{{ old('exchange_rate', $currency->exchange_rate) }}" 
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                                       step="0.0001"
                                       min="0.0001"
                                       required>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Wechselkurs zur Basiswährung</p>
                        </div>

                        <div class="sm:col-span-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_default" id="is_default" value="1" {{ old('is_default', $currency->is_default) ? 'checked' : '' }}
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="is_default" class="ml-2 block text-sm text-gray-900">
                                    Als Standard-Währung festlegen
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Diese Währung wird als Standard für neue Rechnungen verwendet</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-4 sm:px-6 border-t border-gray-200">
                    <a href="{{ route('currencies.index') }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Abbrechen
                    </a>
                    <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Automatische Großschreibung des Währungscodes
        document.getElementById('code').addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    </script>
</x-layout> 