<x-layout>
    <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-5">
            <!-- Linke Spalte: Überschrift -->
            <div class="px-4 sm:px-0">
                <h2 class="text-base font-semibold text-gray-900">Berechtigung bearbeiten</h2>
                <p class="mt-1 text-sm text-gray-600">Aktualisieren Sie die Informationen der Berechtigung "{{ $permissions->name }}".</p>
            </div>

            <!-- Formular -->
            <form action="{{ route('permissions.update', $permissions->id) }}" method="POST" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
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

                <div class="px-4 py-6 sm:p-6">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        
                        <!-- Name -->
                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Name *
                                </div>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" value="{{ old('name', $permissions->name) }}" 
                                       class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 @error('name') outline-red-500 @enderror"
                                       placeholder="z.B. view_customers"
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
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Beschreibung
                                </div>
                            </label>
                            <div class="mt-2">
                                <textarea name="description" id="description" rows="3"
                                          class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 @error('description') outline-red-500 @enderror"
                                          placeholder="Kurze Beschreibung der Berechtigung...">{{ old('description', $permissions->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategorie -->
                        <div class="sm:col-span-6">
                            <label for="category_type" class="block text-sm font-medium text-gray-900">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Kategorie
                                </div>
                            </label>
                            
                            <!-- Kategorie-Auswahl -->
                            <div class="mt-2 space-y-3">
                                <div>
                                    <label class="flex items-center">
                                        <input type="radio" name="category_type" value="existing" class="mr-2" 
                                               {{ old('category_type', 'existing') == 'existing' ? 'checked' : '' }} 
                                               onchange="toggleCategoryInputEdit()">
                                        <span class="text-sm text-gray-700">Bestehende Kategorie auswählen</span>
                                    </label>
                                </div>
                                
                                <div id="existing_category_dropdown">
                                    <select name="existing_category" id="existing_category"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                        <option value="">-- Kategorie auswählen --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ old('existing_category', $permissions->category) == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="flex items-center">
                                        <input type="radio" name="category_type" value="new" class="mr-2" 
                                               {{ old('category_type') == 'new' ? 'checked' : '' }} 
                                               onchange="toggleCategoryInputEdit()">
                                        <span class="text-sm text-gray-700">Neue Kategorie erstellen</span>
                                    </label>
                                </div>
                                
                                <div id="new_category_input" style="display: none;">
                                    <input type="text" name="new_category" id="new_category"
                                           class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600"
                                           placeholder="Name der neuen Kategorie eingeben..."
                                           value="{{ old('new_category') }}">
                                </div>
                                
                                <!-- Hidden field für die finale Kategorie -->
                                <input type="hidden" name="category" id="final_category" value="{{ old('category', $permissions->category) }}">
                            </div>
                            
                            @error('category')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">Wählen Sie eine bestehende Kategorie oder erstellen Sie eine neue</p>
                        </div>
                    </div>
                </div>

                <!-- Schaltflächen -->
                <div class="flex items-center justify-end gap-x-6 border-t border-gray-200 px-4 py-4 sm:px-6">
                    <a href="{{ route('permissions.index') }}" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Abbrechen
                    </a>
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Änderungen speichern
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript für die Kategorien-Auswahl -->
    <script>
        function toggleCategoryInputEdit() {
            const categoryType = document.querySelector('input[name="category_type"]:checked').value;
            const existingDropdown = document.getElementById('existing_category_dropdown');
            const newInput = document.getElementById('new_category_input');
            const finalCategory = document.getElementById('final_category');
            
            if (categoryType === 'existing') {
                existingDropdown.style.display = 'block';
                newInput.style.display = 'none';
                // Set value from dropdown
                const selectedCategory = document.getElementById('existing_category').value;
                finalCategory.value = selectedCategory;
            } else {
                existingDropdown.style.display = 'none';
                newInput.style.display = 'block';
                // Clear final category
                finalCategory.value = '';
            }
        }
        
        // Event listeners
        document.getElementById('existing_category').addEventListener('change', function() {
            document.getElementById('final_category').value = this.value;
        });
        
        document.getElementById('new_category').addEventListener('input', function() {
            document.getElementById('final_category').value = this.value;
        });
        
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if current category exists in dropdown
            const currentCategory = '{{ old('category', $permissions->category) }}';
            const existingCategories = @json($categories);
            
            if (currentCategory && !existingCategories.includes(currentCategory)) {
                // Current category is not in existing categories, set to new
                document.querySelector('input[name="category_type"][value="new"]').checked = true;
                document.getElementById('new_category').value = currentCategory;
            }
            
            toggleCategoryInputEdit();
        });
    </script>
</x-layout>
