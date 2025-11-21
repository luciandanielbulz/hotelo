<div class="mt-2">
    @if($customer->companyname)
        <p class="text-sm text-gray-900 font-medium">{{ $customer->companyname }}</p>
    @endif
    @if($customer->customername)
        <p class="text-sm text-gray-900">{{ $customer->customername }}</p>
    @endif
    @if($customer->address)
        <p class="text-sm text-gray-700">{{ $customer->address }}</p>
    @endif
    @if($customer->postalcode || $customer->location)
        <p class="text-sm text-gray-700">{{ $customer->postalcode }} {{ $customer->location }}</p>
    @endif
    @if($customer->country)
        <p class="text-sm text-gray-700">{{ $customer->country }}</p>
    @endif
</div>
