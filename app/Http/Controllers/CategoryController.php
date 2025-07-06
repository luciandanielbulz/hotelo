<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Kategorien zu verwalten.');
        }

        $user = Auth::user();
        $clientId = $user->client_id;
        
        $categories = Category::forClient($clientId)
            ->orderBy('name')
            ->get();
            
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Kategorien zu erstellen.');
        }

        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert. Sie haben keine Berechtigung, Kategorien zu erstellen.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        $user = Auth::user();
        $validated['client_id'] = $user->client_id;

        // Prüfe ob Kategorie-Name bereits für diesen Client existiert
        $exists = Category::where('client_id', $user->client_id)
            ->where('name', $validated['name'])
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'Eine Kategorie mit diesem Namen existiert bereits.'])
                ->withInput();
        }

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategorie erfolgreich erstellt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($category->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($category->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($category->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);

        // Prüfe ob Kategorie-Name bereits für diesen Client existiert (außer für diese Kategorie)
        $exists = Category::where('client_id', $category->client_id)
            ->where('name', $validated['name'])
            ->where('id', '!=', $category->id)
            ->exists();
            
        if ($exists) {
            return redirect()->back()
                ->withErrors(['name' => 'Eine Kategorie mit diesem Namen existiert bereits.'])
                ->withInput();
        }

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategorie erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Prüfe Berechtigung und Zugehörigkeit
        if (!Auth::user()->hasPermission('manage_categories')) {
            abort(403, 'Zugriff verweigert.');
        }

        if ($category->client_id !== Auth::user()->client_id) {
            abort(403, 'Zugriff verweigert.');
        }

        // Prüfe ob Kategorie in Verwendung ist
        $inUse = $category->invoiceUploads()->count() > 0;
        
        if ($inUse) {
            $count = $category->invoiceUploads()->count();
            return redirect()->route('categories.index')
                ->with('error', 'Diese Kategorie kann nicht gelöscht werden, da sie noch von ' . $count . ' Rechnung(en) verwendet wird.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategorie erfolgreich gelöscht.');
    }
}
