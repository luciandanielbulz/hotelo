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

    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-5 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Logo bearbeiten</h2>
            <p class="mt-1 text-sm text-gray-600">Ändern Sie die gewünschten Informationen für das Logo.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('logos.update', $logo->id) }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-3">
            @csrf
            @method('PUT')

            <div class="grid md:grid-cols-2 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Name -->
                <div class="sm:col-span-1">
                    <x-input 
                        name="name" 
                        type="text" 
                        placeholder="Name" 
                        label="Name" 
                        :value="old('name', $logo->name)" />
                </div>

                <!-- Client-Auswahl -->
                <div class="sm:col-span-1">
                    <x-dropdown_body 
                        name="client_id" 
                        id="client_id" 
                        :options="$clients->pluck('clientname', 'id')" 
                        :selected="old('client_id', $logo->client_id)" 
                        label="Client"
                        placeholder="Bitte auswählen"
                        class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>
            </div>

            <div class="grid md:grid-cols-2 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Datei hochladen -->
                <div class="sm:col-span-1">
                    <label for="filename" class="block text-sm font-medium text-gray-900">Neues Logo hochladen (optional)</label>
                    <div class="mt-2">
                        <input type="file" name="file" id="filename" class="block w-full text-gray-900 focus:outline-indigo-600">
                        <p class="text-sm text-gray-500 mt-1">Aktuelle Datei: {{ $logo->filename }}</p>
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('logos.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Aktualisieren
                </button>
            </div>
        </form>
    </div>
</x-layout>
