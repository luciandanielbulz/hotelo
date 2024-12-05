<x-layout>
    <div class="space-y-10 divide-y divide-gray-900/10">
        <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
            <div class="px-4 sm:px-0">
                <h3 class="text-base font-semibold text-gray-900">Rechnung per E-Mail versenden</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Bitte geben Sie die E-Mail-Adresse, den Betreff und die Nachricht ein, um die Rechnung zu versenden.
                </p>
            </div>
            <form class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2" id="myForm" method="post">
                <div class="px-4 py-6 sm:p-8">
                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-900">E-Mail Adresse:</label>
                            <div class="mt-2">
                                <input type="email" id="email" name="email" value="{{ $clientdata->email }}"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
                                    required>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="subject" class="block text-sm font-medium text-gray-900">Betreff:</label>
                            <div class="mt-2">
                                <input type="text" id="subject" name="subject" value="{{ $emailsubject }}"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600"
                                    required>
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="message" class="block text-sm font-medium text-gray-900">Nachricht:</label>
                            <div class="mt-2">
                                <textarea id="message" name="message" rows="5"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:outline-indigo-600">
                                    {{ $emailbody }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                    <a href="{{ route('invoice.index') }}"
                        class="text-sm font-semibold text-gray-900">Zurück</a>
                    <button type="button" id="sendEmailButton"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline focus:outline-2 focus:outline-indigo-600">Absenden</button>
                </div>
            </form>
        </div>
    </div>

    <div id="loadingSpinner" class="mt-6 text-center hidden">
        <div class="spinner-border text-indigo-600" role="status">
            <span class="sr-only">Laden...</span>
        </div>
    </div>

    <script>
        // Dein JavaScript bleibt unverändert
    </script>
</x-layout>
