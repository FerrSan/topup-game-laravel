<?php


namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Product;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::active()->ordered();
        
        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }
        
        $games = $query->paginate(12);
        
        return view('games.index', compact('games'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)
            ->active()
            ->firstOrFail();
            
        $products = $game->products()
            ->active()
            ->ordered()
            ->get();
            
        $relatedGames = Game::active()
            ->where('category', $game->category)
            ->where('id', '!=', $game->id)
            ->ordered()
            ->take(4)
            ->get();

        return view('games.show', compact('game', 'products', 'relatedGames'));
    }
}