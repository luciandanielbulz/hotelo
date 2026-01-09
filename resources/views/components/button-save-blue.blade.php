<button 
    type="{{ $type ?? 'submit' }}"
    @if(isset($form)) form="{{ $form }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg hover:bg-blue-800 hover:shadow-2xl hover:scale-105 active:scale-95 transition-all duration-300']) }}>
    @if($showIcon ?? true)
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    @endif
    {{ $name ?? $slot }}
</button>
