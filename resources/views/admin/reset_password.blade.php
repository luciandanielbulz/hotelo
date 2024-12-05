<x-layout>
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <!-- Logo und Titel -->

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">

            <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
                <h2 class="mt-6 text-center text-2xl font-bold tracking-tight text-gray-900">
                    Passwort zurücksetzen
                </h2>
                <form class="space-y-6" action="{{ route('users.reset-password', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Neues Passwort -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900">Neues Passwort</label>
                        <div class="mt-2">
                            <input type="password" name="password" id="password" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <!-- Passwort bestätigen -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-900">Passwort bestätigen</label>
                        <div class="mt-2">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 placeholder:text-gray-400 focus:outline-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <!-- Absenden -->
                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Passwort zurücksetzen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
