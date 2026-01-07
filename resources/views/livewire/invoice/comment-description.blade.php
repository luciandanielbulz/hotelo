<div>

    
    <div class="grid grid-cols-1 gap-4 items-end">
        <!-- Beschreibung (intern) -->
        <div>
            <label for="description" class="block text-sm font-bold text-gray-800 mb-1">
                Beschreibung ändern
            </label>
            <input type="text" name="description" id="description" wire:model.lazy="description"  
                   class="block w-full h-11 py-2.5 px-3 rounded-lg bg-white border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:border-blue-900 shadow-md hover:shadow-lg transition-all duration-200 text-gray-900 font-medium placeholder-gray-600"
                   placeholder="Kurze Beschreibung der Rechnung"/>
        </div>

        <!-- Hinweis: Speichert automatisch beim Verlassen des Feldes -->
    </div>
</div>
