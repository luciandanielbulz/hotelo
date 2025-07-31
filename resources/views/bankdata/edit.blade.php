<x-layout>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base font-semibold leading-6 text-gray-900">Bankdatensatz bearbeiten</h1>
                <p class="mt-2 text-sm text-gray-700">Bearbeiten Sie die Details dieser Transaktion.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ isset($returnUrl) ? $returnUrl : route('bankdata.index') }}" class="block rounded-md bg-gray-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                    Zurück zur Liste
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8">
            <form action="{{ route('bankdata.update', $bankData) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Verstecktes Feld für Rückkehr-URL -->
                @if(isset($returnUrl))
                    <input type="hidden" name="return_url" value="{{ $returnUrl }}">
                @endif
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Datum -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Datum</label>
                        <input type="date" 
                               id="date" 
                               name="date" 
                               value="{{ old('date', $bankData->date) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Betrag -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Betrag</label>
                        <input type="number" 
                               id="amount" 
                               name="amount" 
                               step="0.01"
                               value="{{ old('amount', $bankData->amount) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Typ (Einnahmen/Ausgaben) -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700">Typ</label>
                        <select id="type" 
                                name="type"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="expense" {{ old('type', $bankData->type) == 'expense' ? 'selected' : '' }}>Ausgaben</option>
                            <option value="income" {{ old('type', $bankData->type) == 'income' ? 'selected' : '' }}>Einnahmen</option>
                        </select>
                    </div>

                    <!-- Währung -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700">Währung</label>
                        <select id="currency" 
                                name="currency"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="EUR" {{ old('currency', $bankData->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                            <option value="USD" {{ old('currency', $bankData->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                            <option value="CHF" {{ old('currency', $bankData->currency) == 'CHF' ? 'selected' : '' }}>CHF</option>
                        </select>
                    </div>

                    <!-- Kategorie -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Kategorie</label>
                        <select id="category_id" 
                                name="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            <option value="">Keine Kategorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $bankData->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Partner Name -->
                    <div class="sm:col-span-2">
                        <label for="partnername" class="block text-sm font-medium text-gray-700">Partner Name</label>
                        <input type="text" 
                               id="partnername" 
                               name="partnername" 
                               value="{{ old('partnername', $bankData->partnername) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Partner IBAN -->
                    <div>
                        <label for="partneriban" class="block text-sm font-medium text-gray-700">Partner IBAN</label>
                        <input type="text" 
                               id="partneriban" 
                               name="partneriban" 
                               value="{{ old('partneriban', $bankData->partneriban) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Partner BIC -->
                    <div>
                        <label for="partnerbic" class="block text-sm font-medium text-gray-700">Partner BIC</label>
                        <input type="text" 
                               id="partnerbic" 
                               name="partnerbic" 
                               value="{{ old('partnerbic', $bankData->partnerbic) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>

                    <!-- Referenz -->
                    <div class="sm:col-span-2">
                        <label for="reference" class="block text-sm font-medium text-gray-700">Referenz</label>
                        <input type="text" 
                               id="reference" 
                               name="reference" 
                               value="{{ old('reference', $bankData->reference) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('bankdata.index') }}" 
                       class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-600">
                        Abbrechen
                    </a>
                    <button type="submit" 
                            class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Speichern
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout> 