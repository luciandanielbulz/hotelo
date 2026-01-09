@if(isset($href))
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-stone-200 text-gray-700 font-medium rounded-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300']) }}>
        @if($showIcon ?? true)
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        @endif
        {{ $name ?? $slot }}
    </a>
@else
    <button 
        type="{{ $type ?? 'button' }}"
        {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-stone-200 text-gray-700 font-medium rounded-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300']) }}>
        @if($showIcon ?? true)
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        @endif
        {{ $name ?? $slot }}
    </button>
@endif
