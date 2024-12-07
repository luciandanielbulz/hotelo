@props(['name', 'id', 'options', 'selected' => null, 'label' => null, 'placeholder' => 'Select an option'])

<div class="relative inline-block text-left w-full">
    {{-- Optionales Label --}}
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-900 mb-1">
            {{ $label }}
        </label>
    @endif

    {{-- Dropdown-Trigger --}}
    <button
        type="button"
        id="{{ $id }}-button"
        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
        aria-expanded="false"
        aria-haspopup="true"
    >
        {{ $selected ? $options[$selected] : $placeholder }}
        <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    {{-- Dropdown-Optionen --}}
    <div
        id="{{ $id }}-menu"
        class="absolute right-0 z-10 mt-2 w-full origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none hidden"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="{{ $id }}-button"
    >
        <div class="py-1" role="none">
            @foreach($options as $value => $text)
                <button
                    type="button"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    data-value="{{ $value }}"
                    role="menuitem"
                    onclick="document.getElementById('{{ $id }}-hidden').value = '{{ $value }}'; document.getElementById('{{ $id }}-button').textContent = '{{ $text }}'; document.getElementById('{{ $id }}-menu').classList.add('hidden');"
                >
                    {{ $text }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Verstecktes Input-Feld --}}
    <input type="hidden" name="{{ $name }}" id="{{ $id }}-hidden" value="{{ $selected }}">
</div>

<script>
    document.getElementById('{{ $id }}-button').addEventListener('click', function () {
        const menu = document.getElementById('{{ $id }}-menu');
        menu.classList.toggle('hidden');
    });

    // Klick außerhalb schließen
    document.addEventListener('click', function (event) {
        const button = document.getElementById('{{ $id }}-button');
        const menu = document.getElementById('{{ $id }}-menu');
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
</script>
