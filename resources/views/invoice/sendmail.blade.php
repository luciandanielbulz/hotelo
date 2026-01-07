<x-layout>
    <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-5">
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold text-gray-900">Rechnung per E-Mail versenden</h2>
            <p class="mt-1 text-sm text-gray-600">Senden Sie die Rechnung an die gewünschte E-Mail-Adresse.</p>
        </div>

        <form method="post" action="{{ route('sendinvoice.email') }}" id="emailForm" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-3">
            @csrf
            <input type="hidden" name="invoice_id" value="{{ $clientdata->invoice_id }}">
            <input type="hidden" name="invoice_status" value="{{ $invoiceStatus }}" id="invoiceStatus">


            <div class="col-span-3 px-6 pt-6">
                <label for="email" class="block text-sm font-medium text-gray-900">E-Mail Adresse:</label>
                <div class="mt-1">
                    <input type="email" id="email" name="email" value="{{ $clientdata->email }}" class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-500 sm:text-sm" required>
                </div>
            </div>


            <div class="col-span-3 px-6 pt-3">
                <label for="copy_email" class="block text-sm font-medium text-gray-900">Kopie CC:</label>
                <div class="mt-1">
                    <input type="email" id="copy_email" name="copy_email" value="{{ old('copy_email', $clientdata->sender_email) }}" class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-500 sm:text-sm">
                </div>
            </div>



            <div class="sm:col-span-1 md:col-span-1 px-6 pt-3 pb-6">
                <label for="subject" class="block text-sm font-medium text-gray-900">Betreff:</label>
                <div class="mt-1">
                    <input type="text" id="subject" name="subject" value="{{ $emailsubject }}" class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-blue-500 sm:text-sm" required>
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

            <div class="sm:col-span-6 md:col-span-3 px-6 pb-6">
                <label for="message" class="block text-sm font-medium text-gray-900">Nachricht:</label>
                <div class="mt-1">
                    <textarea name="message" id="message" rows="20" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 focus:outline-indigo-600">{{ old('signature', $emailbody) }}</textarea>
                </div>
            </div>


            <div class="flex items-center justify-end gap-x-6 border-gray-900/10 px-4 py-4 sm:px-8 md:pb-4">
                <a href="{{ route('invoice.index') }}" class="text-sm font-semibold text-gray-900">Zurück</a>
                <button type="submit" id="submitButton" class="rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-blue-500">Absenden</button>
            </div>
        </form>
    </div>

    <!-- Modal für Entwurf-Status -->
    @if($isDraft)
    <div id="draftModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Rechnung im Status "Entwurf"
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Die Rechnung befindet sich im Status "Entwurf". Möchten Sie die Rechnung wirklich in diesem Status versenden?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirmSendButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-800 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                        OK
                    </button>
                    <button type="button" id="cancelSendButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-700 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Abbrechen
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

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

                // Prüfe ob Rechnung im Status "Entwurf" ist
                @if($isDraft)
                let formSubmitted = false;
                let modalConfirmed = false;

                // Formular-Absendung abfangen
                $('#emailForm').on('submit', function(e) {
                    if (!modalConfirmed) {
                        e.preventDefault();
                        // Modal anzeigen
                        $('#draftModal').removeClass('hidden');
                    }
                });

                // OK-Button: Formular wirklich absenden
                $('#confirmSendButton').on('click', function() {
                    modalConfirmed = true;
                    $('#draftModal').addClass('hidden');
                    $('#emailForm').submit();
                });

                // Abbrechen-Button: Modal schließen
                $('#cancelSendButton').on('click', function() {
                    $('#draftModal').addClass('hidden');
                });

                // Modal schließen bei Klick außerhalb
                $('#draftModal').on('click', function(e) {
                    if ($(e.target).hasClass('fixed')) {
                        $('#draftModal').addClass('hidden');
                    }
                });
                @endif
            });
        </script>
    @endpush
</x-layout>
