<x-layout>
    <!-- Session-Nachrichten als Toasts anzeigen -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session()->has('success'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: '{{ session('success') }}',
                        type: 'success'
                    }
                }));
            @endif

            @if(session()->has('error'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: '{{ session('error') }}',
                        type: 'error'
                    }
                }));
            @endif

            @if(session()->has('warning'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: '{{ session('warning') }}',
                        type: 'warning'
                    }
                }));
            @endif

            @if(session()->has('info'))
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: '{{ session('info') }}',
                        type: 'info'
                    }
                }));
            @endif
        });
    </script>

    <div class="sm:flex sm:items-center sm:max-w-7xl">
        <div class="sm:flex-auto">
            <h1 class="text-base font-semibold text-gray-900">Mahnwesen</h1>
            <p class="mt-2 text-sm text-gray-700">Übersicht über offene Rechnungen und Mahnungen</p>
        </div>
        @if(auth()->user() && auth()->user()->hasPermission('process_dunning'))
            <div class="mt-4 sm:mt-0">
                <form action="{{ route('dunning.process') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Mahnwesen verarbeiten
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Statistiken -->
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Gesamt</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Erinnerung</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['reminder'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">1. Mahnung</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['first_stage'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">2. Mahnung</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['second_stage'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">3. Mahnung</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['third_stage'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter und Suche -->
    <div class="mt-8 bg-white shadow rounded-lg p-6">
        <form method="GET" action="{{ route('dunning.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Suche</label>
                <input type="text" 
                       name="search" 
                       id="search" 
                       value="{{ $search }}" 
                       placeholder="Rechnungsnummer oder Kunde..."
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div class="sm:w-48">
                <label for="stage" class="block text-sm font-medium text-gray-700 mb-2">Mahnstufe</label>
                <select name="stage" 
                        id="stage" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="all" {{ $stage === 'all' ? 'selected' : '' }}>Alle</option>
                    <option value="1" {{ $stage === '1' ? 'selected' : '' }}>Erinnerung</option>
                    <option value="2" {{ $stage === '2' ? 'selected' : '' }}>1. Mahnung</option>
                    <option value="3" {{ $stage === '3' ? 'selected' : '' }}>2. Mahnung</option>
                    <option value="4" {{ $stage === '4' ? 'selected' : '' }}>3. Mahnung</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Filtern
                </button>
                @if($search || $stage !== 'all')
                    <a href="{{ route('dunning.index') }}" 
                       class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Zurücksetzen
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Rechnungen Tabelle -->
    <div class="mt-8 flow-root">
        @if($invoices->count() > 0)
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Rechnungsnummer</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Kunde</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Rechnungsdatum</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Fälligkeitsdatum</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Mahnstufe</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tage überfällig</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Aktionen</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($invoices as $invoice)
                                    @php
                                        $dueDate = $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date) : null;
                                        $daysOverdue = $dueDate ? max(0, \Carbon\Carbon::today()->diffInDays($dueDate)) : 0;
                                        $stageLabels = [
                                            0 => ['label' => 'Keine', 'color' => 'gray'],
                                            1 => ['label' => 'Erinnerung', 'color' => 'yellow'],
                                            2 => ['label' => '1. Mahnung', 'color' => 'orange'],
                                            3 => ['label' => '2. Mahnung', 'color' => 'red'],
                                            4 => ['label' => '3. Mahnung', 'color' => 'red'],
                                        ];
                                        $stageInfo = $stageLabels[$invoice->dunning_stage ?? 0] ?? $stageLabels[0];
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ $invoice->number }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $invoice->customer->companyname ?? $invoice->customer->customername ?? 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ $invoice->date ? \Carbon\Carbon::parse($invoice->date)->format('d.m.Y') : 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            @if($dueDate)
                                                <span class="{{ $dueDate->isPast() ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                    {{ $dueDate->format('d.m.Y') }}
                                                </span>
                                            @else
                                                <span class="text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $stageInfo['color'] }}-100 text-{{ $stageInfo['color'] }}-800">
                                                {{ $stageInfo['label'] }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            @if($daysOverdue > 0)
                                                <span class="text-red-600 font-medium">{{ $daysOverdue }} Tage</span>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <a href="{{ route('invoice.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                Anzeigen
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $invoices->links() }}
            </div>
        @else
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Keine Mahnungen gefunden</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search || $stage !== 'all')
                        Versuchen Sie, Ihre Filter anzupassen.
                    @else
                        Es gibt derzeit keine Rechnungen mit Mahnstufe.
                    @endif
                </p>
            </div>
        @endif
    </div>
</x-layout>
