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
            <h2 class="text-base font-semibold text-gray-900">Neues Logo hochladen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um ein neues Logo hochzuladen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('logos.store') }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-3">
            @csrf

            <div class="grid md:grid-cols-2 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Name -->
                <div class="sm:col-span-1">
                    <x-input name="name" type="text" placeholder="Name" label="Name" value="" />
                </div>

                <div class="sm:col-span-1">
                    <x-dropdown_body name="client_id" id="client_id" value="" :options="$clients->pluck('clientname', 'id')" :selected="1" label="Klient" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>

            </div>
            <div class="grid md:grid-cols-2 sm:grid-cols-1 pb-4 gap-x-6">
                    <!-- Datei hochladen -->
                    <div class="sm:col-span-1">
                        <label for="filename" class="block text-sm/6 font-bold text-gray-800 mb-2">Datei hochladen</label>
                        <div class="mt-1">
                            <input type="file" name="file" id="filename" required class="block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200">

                        </div>
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('logos.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-indigo-600">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
