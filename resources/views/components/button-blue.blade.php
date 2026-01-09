<button 
    type="{{ $type ?? 'submit' }}"
    @if(isset($form)) form="{{ $form }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300']) }}>
    {{ $name ?? $slot }}
</button>
