<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-5">
        <!-- Linke Spalte: Überschrift -->
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Rolle bearbeiten</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte aktualisieren Sie die Informationen der Rolle.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid max-w-2xl grid-cols-3 gap-x-6 gap-y-8 sm:grid-cols-2 md:col-span-2">
                    <!-- Rollenname -->
                    <div class="sm:col-span-2">
                        <x-input name="name" type="text" placeholder="Name" label="Name" value="{{  $role->name }}" />
                    </div>
            </div>
            <div class="grid max-w-2xl grid-cols-3 gap-x-6 gap-y-8 sm:grid-cols-2 md:col-span-2">


                <div class="sm:col-span-2">
                    <x-input name="description" type="text" placeholder="Beschreibung" label="Beschreibung" value="{{  $role->name }}" />
                </div>


            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('roles.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Änderungen speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
