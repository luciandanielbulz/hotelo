<x-layout>
    <!-- Optimierter Header -->
    <div class="mb-6">
        <!-- Mobile Header - zentriert -->
        <div class="md:hidden text-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Bestellungen</h1>
            <p class="text-gray-600 mt-1">Verwalten Sie alle eingehenden Bestellungen</p>
        </div>
        
        <!-- Desktop Header -->
        <div class="hidden md:flex md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Bestellungen</h1>
                <p class="text-gray-600">Verwalten Sie alle eingehenden Bestellungen</p>
            </div>
        </div>
    </div>

    <!-- Statistiken -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg p-4 border border-gray-200">
            <div class="text-sm text-gray-600">Gesamt</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
            <div class="text-sm text-yellow-700">Ausstehend</div>
            <div class="text-2xl font-bold text-yellow-900">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
            <div class="text-sm text-blue-700">In Bearbeitung</div>
            <div class="text-2xl font-bold text-blue-900">{{ $stats['processed'] }}</div>
        </div>
        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
            <div class="text-sm text-green-700">Abgeschlossen</div>
            <div class="text-2xl font-bold text-green-900">{{ $stats['completed'] }}</div>
        </div>
    </div>

    <!-- Filter und Suche -->
    <div class="bg-white rounded-lg p-4 mb-6 border border-gray-200">
        <form method="GET" action="{{ route('orders.index', $client) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-300">
                    <option value="">Alle</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Ausstehend</option>
                    <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>In Bearbeitung</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Abgeschlossen</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Storniert</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plan</label>
                <select name="plan" class="w-full rounded-lg border-gray-300">
                    <option value="">Alle</option>
                    <option value="starter" {{ request('plan') == 'starter' ? 'selected' : '' }}>Starter</option>
                    <option value="enterprise" {{ request('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Suche</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Name, E-Mail, Firma..." 
                       class="w-full rounded-lg border-gray-300">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-900 text-white px-4 py-2 rounded-lg hover:bg-blue-800">
                    Filtern
                </button>
            </div>
        </form>
    </div>

    <!-- Bestellungen Tabelle -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Datum</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-Mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->created_at->format('d.m.Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $order->name }}</div>
                                @if($order->company)
                                    <div class="text-sm text-gray-500">{{ $order->company }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $order->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($order->plan)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $order->plan == 'starter' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ ucfirst($order->plan) }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status == 'processed') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'completed') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    @if($order->status == 'pending') Ausstehend
                                    @elseif($order->status == 'processed') In Bearbeitung
                                    @elseif($order->status == 'completed') Abgeschlossen
                                    @else Storniert
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('orders.show', [$client, $order]) }}" 
                                   class="text-blue-900 hover:text-blue-800 mr-3">
                                    Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                Keine Bestellungen gefunden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
    </div>
</x-layout>
