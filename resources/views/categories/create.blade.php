<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-xl font-semibold text-black">Neue Kategorie erstellen</h2>
            <p class="text-sm mt-1">Erstellen Sie eine neue Kategorie für Ihre Rechnungen und Ausgaben.</p>
        </div>

        <div class="sm:col-span-1 md:col-span-5">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('categories.store') }}" class="p-6">
                    @csrf

                    @if(session('success'))
                        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm text-red-700">
                                        <ul class="list-disc space-y-1 pl-5">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Hauptformular in zwei Spalten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Linke Spalte -->
                        <div class="space-y-6">
                            <!-- Kategorie Name -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-blue-700 mb-2">Kategorie Name *</label>
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                                       placeholder="z.B. Büromaterial"
                                       required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Beschreibung -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-blue-700 mb-2">Beschreibung</label>
                                <textarea name="description" 
                                          id="description"
                                          rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                                          placeholder="Optionale Beschreibung für diese Kategorie...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Keywords für automatische Kategorisierung -->
                            <div>
                                <label for="keywords" class="block text-sm font-bold text-blue-700 mb-2">
                                    Keywords für Auto-Kategorisierung
                                    <span class="text-xs text-gray-500 font-normal">(kommagetrennt)</span>
                                </label>
                                <textarea name="keywords" 
                                          id="keywords"
                                          rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                                          placeholder="z.B. büro, computer, laptop, monitor">{{ old('keywords') }}</textarea>
                                <p class="mt-1 text-xs text-gray-600">
                                    Diese Keywords werden für die automatische Kategorisierung von Bankdaten verwendet. 
                                    Mehrere Keywords mit Komma trennen.
                                </p>
                                @error('keywords')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Rechte Spalte -->
                        <div class="space-y-6">
                            <!-- Farbe -->
                            <div>
                                <label for="color" class="block text-sm font-bold text-blue-700 mb-2">Farbe</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" 
                                           name="color" 
                                           id="color"
                                           value="{{ old('color', '#6366f1') }}"
                                           class="w-16 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 cursor-pointer">
                                    <div class="flex-1">
                                        <input type="text" 
                                               id="color_preview" 
                                               value="{{ old('color', '#6366f1') }}"
                                               readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-700 text-sm">
                                    </div>
                                </div>
                                @error('color')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Typ (Einnahmen/Ausgaben) -->
                            <div>
                                <label for="type" class="block text-sm font-bold text-blue-700 mb-2">Typ</label>
                                <select name="type" 
                                        id="type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                                    <option value="expense" {{ old('type', 'expense') == 'expense' ? 'selected' : '' }}>Ausgaben</option>
                                    <option value="income" {{ old('type', 'expense') == 'income' ? 'selected' : '' }}>Einnahmen</option>
                                </select>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Verrechnungsdauer -->
                            <div>
                                <label for="billing_duration_years" class="block text-sm font-bold text-blue-700 mb-2">Verrechnungsdauer (Jahre)</label>
                                <input type="number" 
                                       name="billing_duration_years" 
                                       id="billing_duration_years"
                                       value="{{ old('billing_duration_years', '1') }}"
                                       min="1"
                                       max="50"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                                       placeholder="1">
                                @error('billing_duration_years')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Prozentsatz -->
                            <div>
                                <label for="percentage" class="block text-sm font-bold text-blue-700 mb-2">Prozentsatz (%)</label>
                                <input type="number" 
                                       name="percentage" 
                                       id="percentage"
                                       value="{{ old('percentage', '100.00') }}"
                                       min="0"
                                       max="1000"
                                       step="0.01"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent"
                                       placeholder="100.00">
                                @error('percentage')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="is_active" class="block text-sm font-bold text-blue-700 mb-2">Status</label>
                                <select name="is_active" 
                                        id="is_active"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-transparent">
                                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktiv</option>
                                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inaktiv</option>
                                </select>
                                @error('is_active')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Vorschau -->
                            <div>
                                <label class="block text-sm font-bold text-blue-700 mb-2">Vorschau</label>
                                <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div id="color_dot" class="w-4 h-4 rounded-full border border-gray-300" style="background-color: {{ old('color', '#6366f1') }}"></div>
                                        <span id="name_preview" class="text-sm font-medium text-gray-900">{{ old('name', 'Kategoriename') }}</span>
                                        <span id="type_preview" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ausgaben</span>
                                        <span id="status_preview" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktiv</span>
                                    </div>
                                    <p id="description_preview" class="mt-2 text-sm text-gray-600">{{ old('description', 'Beschreibung der Kategorie...') }}</p>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <span id="billing_preview">Verrechnungsdauer: 1 Jahr(e)</span>
                                        <span class="mx-2">•</span>
                                        <span id="percentage_preview">Prozentsatz: 100.00%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <a href="{{ route('categories.index') }}"
                           class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Abbrechen
                        </a>
                        <button type="submit"
                                class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-800 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Kategorie erstellen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const colorInput = document.getElementById('color');
            const colorPreview = document.getElementById('color_preview');
            const colorDot = document.getElementById('color_dot');
            const nameInput = document.getElementById('name');
            const namePreview = document.getElementById('name_preview');
            const descriptionInput = document.getElementById('description');
            const descriptionPreview = document.getElementById('description_preview');
            const statusSelect = document.getElementById('is_active');
            const statusPreview = document.getElementById('status_preview');
            const typeSelect = document.getElementById('type');
            const typePreview = document.getElementById('type_preview');
            const billingInput = document.getElementById('billing_duration_years');
            const billingPreview = document.getElementById('billing_preview');
            const percentageInput = document.getElementById('percentage');
            const percentagePreview = document.getElementById('percentage_preview');

            // Update color preview
            colorInput.addEventListener('input', function() {
                const color = this.value;
                colorPreview.value = color;
                colorDot.style.backgroundColor = color;
            });

            // Update name preview
            nameInput.addEventListener('input', function() {
                const name = this.value || 'Kategoriename';
                namePreview.textContent = name;
            });

            // Update description preview
            descriptionInput.addEventListener('input', function() {
                const description = this.value || 'Beschreibung der Kategorie...';
                descriptionPreview.textContent = description;
            });

            // Update type preview
            typeSelect.addEventListener('change', function() {
                const type = this.value;
                const isIncome = type === 'income';
                typePreview.textContent = isIncome ? 'Einnahmen' : 'Ausgaben';
                typePreview.className = isIncome 
                    ? 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800'
                    : 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
            });

            // Update billing duration preview
            billingInput.addEventListener('input', function() {
                const years = this.value || '1';
                billingPreview.textContent = `Verrechnungsdauer: ${years} Jahr(e)`;
            });

            // Update percentage preview
            percentageInput.addEventListener('input', function() {
                const percentage = this.value || '100.00';
                percentagePreview.textContent = `Prozentsatz: ${percentage}%`;
            });

            // Update status preview
            statusSelect.addEventListener('change', function() {
                const isActive = this.value === '1';
                statusPreview.textContent = isActive ? 'Aktiv' : 'Inaktiv';
                statusPreview.className = isActive 
                    ? 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800'
                    : 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
            });
        });
    </script>
</x-layout> 