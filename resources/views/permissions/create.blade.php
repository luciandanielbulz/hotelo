<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
        <!-- Linke Spalte: Überschrift -->
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Recht bearbeiten</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um ein neues Recht anzulegen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('permissions.store') }}" method="POST">
            @csrf
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2 md:col-span-2">
                <!-- Rollenname -->
                <div class="sm:col-span-2">
                    <x-input name="name" type="text" placeholder="Name" label="Name" value="" />
                </div>
            </div>
            <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-2 md:col-span-2">
                <!-- Rollenname -->
                <div class="sm:col-span-2">
                    <x-input name="description" type="text" placeholder="Beschreibung" label="Beschreibung" value="" />
                </div>
            </div>


            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6   px-4 py-4 sm:px-8">
                <a href="{{ route('permissions.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-indigo-600">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
