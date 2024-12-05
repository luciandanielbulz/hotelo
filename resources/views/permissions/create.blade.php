<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
        <!-- Linke Spalte: Überschrift -->
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Neues Recht anlegen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um ein neues Recht anzulegen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('permissions.store') }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            @csrf
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Rechtname -->
                    <div class="sm:col-span-3">
                        <label for="name" class="block text-sm font-medium text-gray-900">Rechtname</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-indigo-600">
                        </div>
                    </div>

                    <!-- Beschreibung -->
                    <div class="sm:col-span-3">
                        <label for="description" class="block text-sm font-medium text-gray-900">Beschreibung</label>
                        <div class="mt-2">
                            <input type="text" name="description" id="description" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-indigo-600">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('permissions.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
