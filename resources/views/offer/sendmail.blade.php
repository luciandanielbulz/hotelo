<x-layout>
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Angebot per E-Mail versenden</h1>
                <p class="text-gray-600 mt-1">Senden Sie das Angebot an die gewünschte E-Mail-Adresse</p>
            </div>
            <div>
                <a href="{{ route('offer.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-stone-200 text-gray-700 font-medium rounded-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"/>
                    </svg>
                    Zurück
                </a>
            </div>
        </div>
    </div>

    <!-- Formular Container -->
    <div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 mb-6 border border-stone-200">
        <form method="post" action="{{ route('sendoffer.email') }}" id="emailForm">
            @csrf
            <input type="hidden" name="offer_id" value="{{ $clientdata->offer_id }}">

            <div class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-800 mb-2">E-Mail Adresse:</label>
                    <div class="hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                        <input type="email" id="email" name="email" value="{{ $clientdata->email }}" class="block w-full rounded-lg bg-white/50 backdrop-blur-sm px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-gray-400" required>
                    </div>
                </div>

                <div>
                    <label for="copy_email" class="block text-sm font-bold text-gray-800 mb-2">Kopie CC:</label>
                    <div class="hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                        <input type="email" id="copy_email" name="copy_email" value="{{ old('copy_email', $clientdata->sender_email) }}" class="block w-full rounded-lg bg-white/50 backdrop-blur-sm px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-gray-400">
                    </div>
                </div>

                <div>
                    <label for="subject" class="block text-sm font-bold text-gray-800 mb-2">Betreff:</label>
                    <div class="hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                        <input type="text" id="subject" name="subject" value="" class="block w-full rounded-lg bg-white/50 backdrop-blur-sm px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700 placeholder:text-gray-400" required>
                    </div>
                    <div class="mt-2 flex items-center space-x-2">
                        <svg class="w-7 h-7 text-red-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M7 18H17V16H7V18Z" fill="currentColor"/>
                            <path d="M17 14H7V12H17V14Z" fill="currentColor"/>
                            <path d="M7 10H11V8H7V10Z" fill="currentColor"/>
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C4.34315 2 3 3.34315 3 5V19C3 20.6569 4.34315 22 6 22H18C19.6569 22 21 20.6569 21 19V9C21 5.13401 17.866 2 14 2H6ZM6 4H13V9H19V19C19 19.5523 18.5523 20 18 20H6C5.44772 20 5 19.5523 5 19V5C5 4.44772 5.44772 4 6 4ZM15 4.10002C16.6113 4.4271 17.9413 5.52906 18.584 7H15V4.10002Z" fill="currentColor"/>
                        </svg>
                        <span class="text-sm text-gray-600">{{ $pdf_filename }}</span>
                    </div>
                </div>

                <div>
                    <label for="message" class="block text-sm font-bold text-gray-800 mb-2">Nachricht:</label>
                    <div class="hover:shadow-md hover:scale-[1.02] transition-all duration-300">
                        <textarea name="message" id="message" rows="20" class="block w-full rounded-lg bg-white/50 backdrop-blur-sm px-3 py-2.5 text-base font-medium text-gray-900 border border-stone-200 focus:outline-none focus:ring-2 focus:ring-blue-700"></textarea>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-900 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Absenden
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Ladeanzeige -->
    <div id="loadingSpinner" class="hidden text-center mt-4">
        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500"></div>
        <span class="text-gray-600">Laden...</span>
    </div>

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#message').summernote({
                    height: 300, // Höhe des Editors
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Geben Sie hier Ihren Emailtext ein...',
                });
            });
        </script>
    @endpush
</x-layout>
