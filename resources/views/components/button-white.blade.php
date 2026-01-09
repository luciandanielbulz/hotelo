@if(isset($href))
    <a href="{{ $href }}"
       {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-stone-200 text-gray-700 font-medium rounded-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300']) }}>
       {{ $name ?? $slot }}
    </a>
@else
    <button 
        type="{{ $type ?? 'button' }}"
        {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white/60 backdrop-blur-lg border border-stone-200 text-gray-700 font-medium rounded-lg hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300']) }}>
        {{ $name ?? $slot }}
    </button>
@endif
