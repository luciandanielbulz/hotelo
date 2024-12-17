<x-layout>
    <div class="grid grid-cols-1 gap-x-8 border-b border-gray-900/10 pb-12 md:grid-cols-7 sm:grid-cols-1">
        <div class="py-2 px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">JSON-Datei hochladen</h2>
            <p class="mt-1 text-sm text-gray-600">Bitte aktualisieren Sie die Informationen des Klienten.</p>
        </div>

    <form action="{{ route('bankdata.upload') }}" method="POST" enctype="multipart/form-data" class="sm:col-span-1 md:col-span-5">
        @csrf
        <div class="sm:col-span-6">
            <label for="json_file" class="block text-sm font-medium text-gray-900">Wähle eine JSON-Datei:</label>

            <input class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600" type="file" name="json_file" id="json_file" accept=".json" required>
            <button class="inline-block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" type="submit">Hochladen</button>
        </div>
    </form>
</x-layout>
