<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">JSON-Datei hochladen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte aktualisieren Sie die Informationen des Klienten.</p>
        </div>

    <form action="{{ route('bankdata.upload') }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-5">
        @csrf
        <div class="sm:col-span-6">
            <label for="json_file" class="block text-sm font-bold text-gray-800 mb-2">Wähle eine JSON-Datei:</label>

            <input class="block w-full rounded-md bg-white px-3 py-2.5 text-base font-medium text-gray-900 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 shadow-md hover:shadow-lg transition-all duration-200" type="file" name="json_file" id="json_file" accept=".json" required>
            <button class="mt-4 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg font-semibold text-sm text-white shadow-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 hover:shadow-xl transition-all duration-300" type="submit">Hochladen</button>
        </div>
    </form>
</x-layout>
