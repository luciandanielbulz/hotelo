<x-layout>
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Kunden</h1>
            <p class="mt-2 text-sm text-gray-700">Eine Liste aller Kunden in Ihrem Konto, inklusive Name, Adresse, E-Mail und mehr.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
            <x-button route="{{ route('customer.create') }}" value="+ Neu" />
        </div>
    </div>

    <div class="mt-8">
        <livewire:customer.customer-table />
    </div>
</x-layout>
