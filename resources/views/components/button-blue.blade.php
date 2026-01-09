<button 
    type="{{ $type ?? 'submit' }}"
    @if(isset($form)) form="{{ $form }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 text-white font-semibold rounded-lg hover:from-blue-800 hover:via-blue-700 hover:to-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300 shadow-lg hover:shadow-xl']) }}>
    {{ $name ?? $slot }}
</button>
