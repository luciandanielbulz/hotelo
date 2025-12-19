<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ \App\Helpers\TemplateHelper::getFaviconPath() }}">
    <link rel="alternate icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sundanese:wght@400;700&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css">


    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>




    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Stack for additional styles -->
    @stack('styles')
</head>


<body>
    <div class="min-h-screen bg-gray-50">
        {{-- Flowbite Navbar für Tablets und Mobile (unter lg) --}}
        <div class="lg:hidden">
            <x-navbar-tablet />
        </div>

        <div class="flex">
            <!-- Sidebar für Desktop (lg+) -->
            <aside class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
                @include('components.sidebar', ['user' => auth()->user()])
            </aside>

            <!-- Main content -->
            <main class="w-full xs:px-4 md:px-4 lg:pl-64 lg:px-0">
                <div class="mx-auto sm:px-6 lg:px-8">
                    <div class="py-4 max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Global Toast Container (oben rechts) -->
    <div id="toast-root" class="fixed top-4 right-4 z-[100000] space-y-3" x-data="{ toasts: [] }"
         x-init="
            window.addEventListener('notify', (e) => {
                const id = Date.now() + Math.random();
                let payload = Array.isArray(e.detail) ? e.detail[0] : e.detail;
                if (payload === undefined || payload === null) payload = {};
                const message = typeof payload === 'string' ? payload : (payload.message ?? 'Erfolgreich gespeichert.');
                const type = typeof payload === 'object' && payload.type ? payload.type : 'success';
                toasts.push({ id, message, type });
                setTimeout(() => { toasts = toasts.filter(t => t.id !== id); }, 3000);
            });
         ">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-transition.opacity
                 class="rounded-lg shadow-lg border px-4 py-3 min-w-64 max-w-sm"
                 :class="toast.type === 'success' ? 'bg-green-50 border-green-200 text-green-900' : (toast.type === 'error' ? 'bg-red-50 border-red-200 text-red-900' : 'bg-gray-50 border-gray-200 text-gray-900')">
                <div class="flex items-start">
                    <div class="mt-0.5 mr-2" :class="toast.type === 'success' ? 'text-green-500' : (toast.type === 'error' ? 'text-red-500' : 'text-gray-500')">
                        <svg x-show="toast.type === 'success'" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>
                        <svg x-show="toast.type === 'error'" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" /></svg>
                        <svg x-show="toast.type !== 'success' && toast.type !== 'error'" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path d="M2 5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0116 9v6a2 2 0 01-2 2H4a2 2 0 01-2-2V5z" /></svg>
                    </div>
                    <div class="text-sm font-medium" x-text="toast.message"></div>
                </div>
            </div>
        </template>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
