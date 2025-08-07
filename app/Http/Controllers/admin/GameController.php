<?php 
// 7. Admin/GameController
// app/Http/Controllers/Admin/GameController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::withCount('products', 'transactions')
            ->ordered()
            ->paginate(10);
            
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:games,slug',
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'category' => 'required|in:mobile,pc,console',
            'image' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'form_fields' => 'nullable|json'
        ]);
        
        if (!isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('games', 'public');
        }
        
        if ($request->hasFile('banner')) {
            $validated['banner'] = $request->file('banner')->store('games/banners', 'public');
        }
        
        if (isset($validated['form_fields'])) {
            $validated['form_fields'] = json_decode($validated['form_fields'], true);
        }
        
        Game::create($validated);
        
        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil ditambahkan.');
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:games,slug,' . $game->id,
            'description' => 'nullable|string',
            'publisher' => 'nullable|string|max:255',
            'category' => 'required|in:mobile,pc,console',
            'image' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:4096',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'form_fields' => 'nullable|json'
        ]);
        
        if ($request->hasFile('image')) {
            if ($game->image) {
                Storage::disk('public')->delete($game->image);
            }
            $validated['image'] = $request->file('image')->store('games', 'public');
        }
        
        if ($request->hasFile('banner')) {
            if ($game->banner) {
                Storage::disk('public')->delete($game->banner);
            }
            $validated['banner'] = $request->file('banner')->store('games/banners', 'public');
        }
        
        if (isset($validated['form_fields'])) {
            $validated['form_fields'] = json_decode($validated['form_fields'], true);
        }
        
        $game->update($validated);
        
        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil diperbarui.');
    }

    public function destroy(Game $game)
    {
        if ($game->image) {
            Storage::disk('public')->delete($game->image);
        }
        
        if ($game->banner) {
            Storage::disk('public')->delete($game->banner);
        }
        
        $game->delete();
        
        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil dihapus.');
    }
}
