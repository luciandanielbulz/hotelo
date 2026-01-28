<div class="bg-white/60 backdrop-blur-lg rounded-xl p-6 border border-stone-200">
    <!-- Dokument-Fußzeile (Angebote & Rechnungen) -->
    <div class="border-b border-gray-900/10 pb-6">
        <h3 class="text-base font-semibold leading-7 text-gray-900 mb-4">Dokument-Fußzeile</h3>
        <div class="grid md:grid-cols-4 sm:grid-cols-1 pb-4 gap-x-6">
            <div class="sm:col-span-6">
                
                <div class="mt-1" wire:ignore>
                    <textarea name="document_footer" id="document_footer" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('document_footer') border-red-500 outline-red-500 @enderror">{!! $footerContent !!}</textarea>
                    @error('document_footer')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mt-3">
                    <button type="button"
                            id="save_footer_btn_{{ $invoiceId }}"
                            class="rounded-md bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 px-3 py-2 text-white hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none">
                        Speichern
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Einbindung von Summernote -->
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#document_footer').summernote({
                    height: 200,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['fullscreen', 'codeview', 'help']]
                    ],
                    placeholder: 'Text, der in Angeboten/Rechnungen unter der Summe angezeigt wird...',
                });
                $('#document_footer').summernote('code', @js($footerContent));

                $('#save_footer_btn_{{ $invoiceId }}').on('click', function(){
                    const html = $('#document_footer').summernote('code');
                    if (window.Livewire) {
                        Livewire.find(@this.__instance.id).set('footerContent', html || '');
                        Livewire.find(@this.__instance.id).call('saveFooter');
                    }
                });
            });
        </script>
    @endpush
</div>


