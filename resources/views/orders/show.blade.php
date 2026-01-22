<x-layout>
    <div class="mb-6">
        <a href="{{ route('orders.index') }}" class="text-blue-900 hover:text-blue-800 mb-4 inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Zurück zur Übersicht
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Bestellung #{{ $order->id }}</h1>
        <p class="text-gray-600">Details der Bestellung</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Hauptinformationen -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Kundendaten -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Kundendaten</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Name</label>
                        <p class="text-gray-900">{{ $order->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">E-Mail</label>
                        <p class="text-gray-900">{{ $order->email }}</p>
                    </div>
                    @if($order->company)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Firma</label>
                            <p class="text-gray-900">{{ $order->company }}</p>
                        </div>
                    @endif
                    @if($order->phone)
                        <div>
                            <label class="text-sm font-medium text-gray-500">Telefon</label>
                            <p class="text-gray-900">{{ $order->phone }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Adresse -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Adresse</h2>
                <div class="space-y-2">
                    <p class="text-gray-900">{{ $order->street }}</p>
                    <p class="text-gray-900">{{ $order->postal_code }} {{ $order->city }}</p>
                    <p class="text-gray-900">{{ $order->country }}</p>
                </div>
            </div>

            <!-- Bestelldetails -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Bestelldetails</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Plan</label>
                        <p class="text-gray-900">
                            @if($order->plan)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $order->plan == 'starter' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ ucfirst($order->plan) }}
                                </span>
                            @else
                                <span class="text-gray-400">Nicht angegeben</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Kleinunternehmer</label>
                        <p class="text-gray-900">
                            @if($order->is_kleinunternehmer)
                                <span class="text-green-600">Ja</span>
                            @else
                                <span class="text-gray-400">Nein</span>
                            @endif
                        </p>
                    </div>
                    @if($order->uid_number)
                        <div>
                            <label class="text-sm font-medium text-gray-500">UID-Nummer</label>
                            <p class="text-gray-900">{{ $order->uid_number }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($order->message)
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Nachricht / Anmerkungen</h2>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $order->message }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Status</h2>
                <form method="POST" action="{{ route('orders.update-status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" 
                            class="w-full rounded-lg border-gray-300 mb-4">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Ausstehend</option>
                        <option value="processed" {{ $order->status == 'processed' ? 'selected' : '' }}>In Bearbeitung</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Abgeschlossen</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Storniert</option>
                    </select>
                </form>
                <div class="text-sm text-gray-500">
                    Erstellt: {{ $order->created_at->format('d.m.Y H:i') }}<br>
                    Aktualisiert: {{ $order->updated_at->format('d.m.Y H:i') }}
                </div>
            </div>

            <!-- Technische Informationen -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Technische Informationen</h2>
                <div class="space-y-2 text-sm">
                    <div>
                        <label class="text-gray-500">IP-Adresse</label>
                        <p class="text-gray-900">{{ $order->ip_address ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-gray-500">Bestell-ID</label>
                        <p class="text-gray-900">#{{ $order->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
