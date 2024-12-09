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

    <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
        <!-- Linke Spalte: Überschrift -->
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Benutzer bearbeiten</h2>
            <p class="mt-1 text-sm text-gray-600">Aktualisieren Sie die Informationen des Benutzers.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            @csrf
            @method('PUT') <!-- Dies wird benötigt, da Laravel PUT-Methoden für Updates verwendet -->

            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Vorname -->
                    <div class="sm:col-span-2">
                        <x-input name="name" type="text" placeholder="Name" label="Name" value="{{ old('name', $user->name) }}" />
                    </div>

                    <!-- Nachname -->
                    <div class="sm:col-span-2">
                        <x-input name="lastname" type="text" placeholder="Nachname" label="Nachname" value="{{ old('lastname', $user->lastname) }}" />
                    </div>

                    <!-- Loginname -->
                    <div class="sm:col-span-2">
                        <x-input name="login" type="text" placeholder="Loginname" label="Loginname" value="{{ old('login', $user->username) }}" />
                    </div>

                    <!-- Klient -->
                    <div class="sm:col-span-2">
                        <x-dropdown_body name="client_id" id="client_id" :options="$clients->pluck('clientname', 'id')" :selected="old('clientname', $user->client_id)" label="Klient" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-2">
                        <x-input name="lemailogin" type="text" placeholder="Email" label="E-Mail" value="{{ old('login', $user->email) }}" />
                    </div>

                    <!-- Aktiv -->
                    <div class="sm:col-span-2">
                        <x-dropdown_body name="isactive" id="isactive" :options="['1' => 'Ja', '0' => 'Nein']" :selected="old('isactive', $user->isactive)" label="Aktiv" placeholder="Bitte auswählen" class="w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" />
                    </div>

                    <!-- Rolle -->
                    <div class="sm:col-span-2">
                        <x-dropdown_body name="role_id" id="role_id" :options="$roles->pluck('name', 'id')" :selected="old('name', $user->role_id)" label="Rolle" placeholder="Bitte auswählen" />
                    </div>

                    <!-- Versteckte Felder -->
                    <input type="hidden" name="selecteduserid" value="{{ $user->id }}">
                    <input type="hidden" name="userid" value="{{ $user->id }}">

                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Änderungen speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
