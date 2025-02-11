<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Konto löschen') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Wenn Ihr Konto gelöscht wird, werden alle Ressourcen und Daten dauerhaft gelöscht. Bevor Sie Ihr Konto löschen, laden Sie bitte alle Daten oder Informationen herunter, die Sie behalten möchten.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Konto löschen') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Sind Sie sicher, dass Sie Ihr Konto löschen möchten?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Wenn Ihr Konto gelöscht wird, werden alle Ressourcen und Daten dauerhaft gelöscht. Bitte geben Sie Ihr Passwort ein, um zu bestätigen, dass Sie das Konto dauerhaft löschen möchten.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Passwort') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Passwort') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Abbrechen') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Konto löschen') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
