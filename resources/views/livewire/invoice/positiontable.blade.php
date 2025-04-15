<div class="mt-8 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-black sm:pl-6">Id</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Nummer</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Datum</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kunde</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Beschreibung</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Rechnungsbetrag</th>
                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">versand am</th>
                            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($invoices as $invoice)
                            <tr data-id="{{ $invoice->id }}" class="hover:bg-indigo-100 cursor-pointer">
                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm  text-black sm:pl-6">{{ $invoice->invoice_id }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $invoice->number }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ \Carbon\Carbon::parse($invoice->date)->translatedFormat('d.m.Y') }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ \Illuminate\Support\Str::limit($invoice->customername ?? $invoice->companyname ?? 'Kein Kunde', 30, '...') }} </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ $invoice->description ?? 'Kein Kommentar' }}</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">{{ number_format($invoice->total_price, 2, ',', '.') }} €</td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-black">
                                    {{ $invoice->sent_date ? \Carbon\Carbon::parse($invoice->sent_date)->translatedFormat('d.m.Y h:i') : '-' }}
                                </td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <div class="flex flex-wrap gap-2 justify-end items-center">
                                        <!-- Vorschau Button -->
                                        <button
                                            onclick="window.open('{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'I']) }}', '_blank')"
                                            class="text-indigo-600 hover:text-orange-900 ml-4">
                                            Vorschau
                                        </button>

                                        <!-- Bearbeiten Link -->
                                        <a href="{{ route('invoice.edit', $invoice->invoice_id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 ml-4">
                                            Bearbeiten
                                        </a>

                                        <!-- Kopieren Link -->
                                        <a href="{{ route('invoice.copy', $invoice->invoice_id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 ml-4">
                                            Kopieren
                                        </a>

                                        <!-- PDF Button -->
                                        <button
                                            onclick="window.open('{{ route('createinvoice.pdf', ['invoice_id' => $invoice->invoice_id, 'objecttype' => 'invoice', 'prev' => 'D']) }}', '_blank')"
                                            class="text-indigo-600 hover:text-orange-900 ml-4">
                                            PDF
                                        </button>

                                        <!-- Senden Form -->
                                        <form action="{{ route('invoice.sendmail') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="objectid" value="{{ $invoice->invoice_id }}">
                                            <input type="hidden" name="objecttype" value="invoice">
                                            <button type="submit"
                                                class="text-indigo-600 hover:text-orange-900 ml-4">
                                                Senden
                                            </button>
                                        </form>

                                        <button
                                            wire:click="archiveInvoice({{ $invoice->invoice_id }})"
                                            class="text-indigo-600 hover:text-orange-900 ml-4">
                                            Archiv
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-3 py-4 text-sm text-gray-500 text-center">Keine Datensätze gefunden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!-- Paginierungslinks -->
<div class="mt-4">
    {{ $invoices->links() }}
</div>
</div>

