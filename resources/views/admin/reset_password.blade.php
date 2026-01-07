<x-layout>
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Logo und Titel -->

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">

            <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
                <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900">
                    Passwort zurücksetzen für: {{ $user->name }} {{ $user->lastname }}
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    E-Mail: {{ $user->email }}
                </p>

                <!-- Erfolgs-/Fehlermeldungen -->
                @if(session('success'))
                    <div class="mt-4 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L8.23 10.661a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mt-4 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6 mt-6" action="{{ route('users.reset-password', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Neues Passwort -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900">Neues Passwort</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" required 
                                minlength="8"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-indigo-600 sm:text-sm"
                                placeholder="Mindestens 8 Zeichen">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Passwort bestätigen -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900">Passwort bestätigen</label>
                        <div class="mt-2">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                minlength="8"
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-indigo-600 sm:text-sm"
                                placeholder="Passwort wiederholen">
                        </div>
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Passwort-Stärke Hinweise -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <h4 class="text-sm font-medium text-blue-900">Passwort-Anforderungen:</h4>
                        <ul class="mt-2 text-sm text-blue-700 list-disc list-inside">
                            <li>Mindestens 8 Zeichen</li>
                            <li>Beide Passwort-Felder müssen identisch sein</li>
                        </ul>
                    </div>

                    <!-- Absenden -->
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 transition-all duration-300">
                            Passwort zurücksetzen
                        </button>
                    </div>

                    <!-- Zurück Button -->
                    <div>
                        <a href="{{ route('users.index') }}"
                            class="flex w-full justify-center rounded-md bg-gray-300 px-3 py-1.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-500">
                            Zurück zur Benutzerliste
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
