<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Rechnung per E-Mail versenden</h2>
            <p class="mt-1 text-sm text-gray-600">Senden Sie die Rechnung an die gewünschte E-Mail-Adresse.</p>
        </div>

        <form method="post" action="{{ route('sendinvoice.email') }}" id="emailForm" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $clientdata->invoice_id }}">
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="email" class="block text-sm font-medium text-gray-900">E-Mail Adresse:</label>
                        <div class="mt-2">
                            <input type="email" id="email" name="email" value="{{ $clientdata->email }}" class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-500 sm:text-sm" required>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="subject" class="block text-sm font-medium text-gray-900">Betreff:</label>
                        <div class="mt-2">
                            <input type="text" id="subject" name="subject" value="{{ $emailsubject }}" class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-500 sm:text-sm" required>
                        </div>
                    </div>

                    <div class="sm:col-span-6">
                        <label for="message" class="block text-sm font-medium text-gray-900">Nachricht:</label>
                        <div class="mt-2">
                            <textarea id="message" name="message" rows="7" class="summernote block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-500 sm:text-sm" required>{{ $emailbody }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <a href="{{ route('invoice.index') }}" class="text-sm font-semibold text-gray-900">Zurück</a>
                <button type="submit" class="rounded-md bg-blue-500 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-600 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-blue-500">Absenden</button>
            </div>
        </form>
    </div>

    <!-- Ladeanzeige -->
    <div id="loadingSpinner" class="hidden text-center mt-4">
        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500"></div>
        <span class="text-gray-600">Laden...</span>
    </div>

    <script>
        $(document).ready(function () {
            // Initialisiere Summernote
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']]
                ]
            });

            // Zeige Lade-Spinner beim Absenden des Formulars
            $('#emailForm').on('submit', function () {
                $('#loadingSpinner').removeClass('hidden');
            });
        });
    </script>
</x-layout>
