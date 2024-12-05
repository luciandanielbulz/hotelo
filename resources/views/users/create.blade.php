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
            <h2 class="text-base font-semibold text-gray-900">Benutzer erstellen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte füllen Sie die folgenden Informationen aus, um einen neuen Benutzer anzulegen.</p>
        </div>

        <!-- Formular -->
        <form action="{{ route('users.store') }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            @csrf <!-- CSRF-Schutz für das Formular -->

            <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <!-- Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-900">Name</label>
                        <div class="mt-2">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Nachname -->
                    <div class="sm:col-span-2">
                        <label for="lastname" class="block text-sm font-medium text-gray-900">Nachname</label>
                        <div class="mt-2">
                            <input type="text" name="lastname" id="lastname" value="{{ old('lastname') }}" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('lastname')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Login -->
                    <div class="sm:col-span-2">
                        <label for="username" class="block text-sm font-medium text-gray-900">Login</label>
                        <div class="mt-2">
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('username')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Klient -->
                    <div class="sm:col-span-3">
                        <label for="client_id" class="block text-sm font-medium text-gray-900">Klient</label>
                        <div class="mt-2">
                            <select name="client_id" id="client_id" required
                                class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->clientname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-3">
                        <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                        <div class="mt-2">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Active -->
                    <div class="sm:col-span-2">
                        <label for="isactive" class="block text-sm font-medium text-gray-900">Aktiv</label>
                        <div class="mt-2">
                            <select name="isactive" id="isactive" required
                                class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                <option value="1" {{ old('isactive') == 1 ? 'selected' : '' }}>Ja</option>
                                <option value="0" {{ old('isactive') == 0 ? 'selected' : '' }}>Nein</option>
                            </select>
                            @error('isactive')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Rolle -->
                    <div class="sm:col-span-2">
                        <label for="role_id" class="block text-sm font-medium text-gray-900">Rolle</label>
                        <div class="mt-2">
                            <select name="role_id" id="role_id" required
                                class="block w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 focus:outline-indigo-600">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Passwort -->
                    <div class="sm:col-span-2">
                        <label for="password" class="block text-sm font-medium text-gray-900">Passwort</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 placeholder:text-gray-400 focus:outline-indigo-600">
                            @error('password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Schaltflächen -->
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('users.index') }}" class="text-sm font-semibold text-gray-900">Abbrechen</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-indigo-600">
                    Speichern
                </button>
            </div>
        </form>
    </div>
</x-layout>
