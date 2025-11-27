<x-layout>
    <div class="space-y-6 sm:space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:gap-y-8 md:grid-cols-5">
            <!-- Linke Spalte: Überschrift -->
            <div class="px-0 sm:px-4">
                <h2 class="text-base font-semibold text-gray-900">Rolle bearbeiten</h2>
                <p class="mt-1 text-sm text-gray-600 hidden sm:block">Aktualisieren Sie die Informationen der Rolle "{{ $role->name }}".</p>
            </div>

            <!-- Formular -->
            <form action="{{ route('roles.update', $role->id) }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 p-3 sm:p-4 bg-red-100 text-red-700 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Fehler beim Speichern:</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="px-4 py-4 sm:py-6 sm:p-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <!-- Rollenname -->
                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="hidden sm:block w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Rollenname *
                                </div>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" 
                                       class="block w-full rounded-md bg-white px-3 py-2 sm:py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 @error('name') outline-red-500 @enderror"
                                       placeholder="Administrator, Benutzer, etc."
                                       required>
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Beschreibung -->
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="hidden sm:block w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Beschreibung
                                </div>
                            </label>
                            <div class="mt-2">
                                <textarea name="description" id="description" rows="3"
                                          class="block w-full rounded-md bg-white px-3 py-2 sm:py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 @error('description') outline-red-500 @enderror"
                                          placeholder="Kurze Beschreibung der Rolle und ihrer Befugnisse...">{{ old('description', $role->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Berechtigungen -->
                        <div class="sm:col-span-6">
                            <div class="border-t border-gray-200 pt-4 sm:pt-6">
                                <livewire:role.permission-selector :role-id="$role->id" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schaltflächen -->
                <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-x-6 border-t border-gray-200 px-4 py-4 sm:px-6">
                    <a href="{{ route('roles.index') }}" class="inline-flex items-center justify-center rounded-md bg-white px-3 py-2.5 sm:py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span>Abbrechen</span>
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-3 py-2.5 sm:py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="w-4 h-4 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Änderungen speichern</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
