<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_orders');
    }

    /**
     * Zeigt alle Bestellungen an
     */
    public function index(Request $request)
    {
        $query = Order::query()->orderBy('created_at', 'desc');
        
        // Filter nach Status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Filter nach Plan
        if ($request->has('plan') && $request->plan !== '') {
            $query->where('plan', $request->plan);
        }
        
        // Suche
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(20);
        
        // Statistiken
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processed' => Order::where('status', 'processed')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];
        
        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Zeigt Details einer Bestellung
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Aktualisiert den Status einer Bestellung
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processed,completed,cancelled',
        ]);
        
        $order->update(['status' => $validated['status']]);
        
        return redirect()->route('orders.index')
            ->with('success', 'Status erfolgreich aktualisiert.');
    }
}
