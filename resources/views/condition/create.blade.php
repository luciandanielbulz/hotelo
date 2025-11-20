<x-layout>
    <!-- Moderner Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Neue Bedingung erstellen</h1>
            <p class="text-gray-600">Fügen Sie eine neue Bedingung hinzu, um Ihre Prozesse zu verwalten</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('condition.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-white/20 text-gray-700 font-medium rounded-lg hover:bg-white/80 transition-all duration-300 shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Zurück zur Übersicht
            </a>
        </div>
    </div>

    <!-- Modernisiertes Formular -->
    <div class="bg-white/60 backdrop-blur-lg rounded-2xl border border-white/20 shadow-xl">
        <form action="{{ route('condition.store') }}" method="POST" class="p-8">
            @csrf

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Fehler beim Speichern</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bedingungsinformationen Sektion -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Bedingungsinformationen
                </h3>
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-12">
                    <div class="sm:col-span-6">
                        <x-input name="conditionname" type="text" placeholder="Bedingungsname eingeben" label="Name der Bedingung" value="{{ old('conditionname') }}" class="rounded-lg bg-white/70 backdrop-blur-sm border-0 shadow-sm focus:ring-2 focus:ring-blue-500 transition-all duration-300" required />
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('condition.index') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-white/70 backdrop-blur-sm border border-gray-200 text-gray-700 font-medium rounded-lg hover:bg-white/90 transition-all duration-300 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Abbrechen
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Bedingung erstellen
                </button>
            </div>
        </form>
    </div>
</x-layout>
