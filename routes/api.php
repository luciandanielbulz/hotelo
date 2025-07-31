<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Route für gefilterte Kategorien
Route::get('/categories', function (Request $request) {
    $user = auth()->user();
    $client_id = $user->client_id;
    $type = $request->get('type', 'all');
    
    $query = \App\Models\Category::where('client_id', $client_id)
        ->where('is_active', true);
    
    if ($type !== 'all') {
        $query->where('type', $type);
    }
    
    $categories = $query->orderBy('name')->get();
    
    return response()->json([
        'categories' => $categories
    ]);
})->middleware('auth:sanctum');
