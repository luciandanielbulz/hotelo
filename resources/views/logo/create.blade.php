<x-layout>
    <!-- Fehlermeldungen -->
    @if($errors->has('error'))
        <div class="px-4 py-2 mb-4 text-sm text-red-700 bg-red-100 rounded-md">
            {{ $errors->first('error') }}
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
            <h2 class="text-base font-semibold text-gray-900">Neues Logo hochladen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um ein neues Logo hochzuladen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('logos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            @csrf

            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Client auswählen -->
                    <div class="sm:col-span-2">
                        <label for="client_id" class="block text-sm font-medium text-gray-900">Client auswählen</label>
                        <div class="mt-2">
                            <select name="client_id" id="client_id" required
                                class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                <option value="">Bitte wählen</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->clientname }}</option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Datei hochladen -->
                    <div class="sm:col-span-2">
                        <label for="filename" class="block text-sm font-medium text-gray-900">Datei hochladen</label>
                        <div class="mt-2">
                            <input type="file" name="file" id="filename" required
                                class="block w-full text-gray-900 focus:outline-indigo-600">
                            @error('file')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('logos.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
