<x-layout>
    <!-- Fehlermeldungen -->
    @if ($errors->any())
        <div class="px-4 py-2 mb-4 text-sm text-red-700 bg-red-100 rounded-md">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <!-- Linke Spalte: Überschrift -->
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Benutzer erstellen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um einen neuen Benutzer anzulegen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('users.store') }}" method="POST" class="sm:col-span-1 md:col-span-5 ">
            @csrf
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
                <!-- Vorname -->
                <div class="sm:col-span-1">
                    <x-input name="name" type="text" placeholder="Name" label="Name" value="{{ old('name') }}" />
                </div>

                <!-- Nachname -->
                <div class="sm:col-span-1">
                    <x-input name="lastname" type="text" placeholder="Nachname" label="Nachname" value="{{ old('lastname') }}" />
                </div>
            </div>

            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Loginname -->
                <div class="sm:col-span-1">
                    <x-input name="username" type="text" placeholder="Loginname" label="Loginname" value="{{ old('username') }}" />
                </div>

                <!-- Klient -->
                <div class="sm:col-span-1">
                    <x-dropdown_body name="client_id" id="client_id" :options="$clients->pluck('clientname', 'id')" :selected="old('client_id', 1)" label="Klient" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>
            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <!-- Email -->
                <div class="sm:col-span-1">
                    <x-input name="email" type="text" placeholder="Email" label="E-Mail" value="{{ old('email') }}" />
                </div>

                <!-- Aktiv -->
                <div class="sm:col-span-1">
                    <x-dropdown_body name="isactive" id="isactive" :options="['1' => 'Ja', '0' => 'Nein']" :selected="old('isactive', '1')" label="Aktiv" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                </div>

                <!-- Rolle -->
                <div class="sm:col-span-1">
                    <x-dropdown_body name="role_id" id="role_id" :options="$roles->pluck('name', 'id')" :selected="old('role_id', 1)" label="Rolle" placeholder="Bitte auswählen" />
                </div>

            </div>
            <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6 border-b">
                <div class="sm:col-span-1">
                    <x-input name="password" type="password" placeholder="Passwort" label="Passwort" value="" />
                </div>
            </div>


            <!-- Schaltflächen -->

            <div class="flex items-center justify-end gap-x-6  px-4 py-4 sm:px-8">
                <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
