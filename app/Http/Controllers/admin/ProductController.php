<?php
// 8. Admin/ProductController
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Game;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('game');
        
        if ($gameId = $request->get('game_id')) {
            $query->where('game_id', $gameId);
        }
        
        $products = $query->ordered()->paginate(20);
        $games = Game::ordered()->get();
        
        return view('admin.products.index', compact('products', 'games'));
    }

    public function create()
    {
        $games = Game::ordered()->get();
        return view('admin.products.create', compact('games'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'bonus' => 'integer|min:0',
            'currency_type' => 'required|string|max:50',
            'amount' => 'required|integer|min:1',
            'is_promo' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);
        
        Product::create($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $games = Game::ordered()->get();
        return view('admin.products.edit', compact('product', 'games'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'game_id' => 'required|exists:games,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'bonus' => 'integer|min:0',
            'currency_type' => 'required|string|max:50',
            'amount' => 'required|integer|min:1',
            'is_promo' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);
        
        $product->update($validated);
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Product berhasil dihapus.');
    }
}

