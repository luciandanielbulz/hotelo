<x-layout>

    <div class="container">

        <div class="row pt-3">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach($tiles as $tile)
                    <a href="{{ $tile['targetFile'] }}" class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400 text-decoration-none">
                        <div class="shrink-0">
                            <i class="{{ $tile['icon'] }} size-10 rounded-full" style="font-size: 2rem; color: {{ $tile['backgroundColor'] }}"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="absolute inset-0" aria-hidden="true"></span>
                            <p class="text-sm font-medium text-gray-900">{{ $tile['title'] }}</p>
                            <p class="truncate text-sm text-gray-500">{{ $tile['text'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>


        </div>

    </div>


</x-layout>
