<?php

// 1. HomeController
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Banner;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::active()
            ->ordered()
            ->take(12)
            ->get();
            
        $banners = Banner::active()
            ->ordered()
            ->get();
            
        $testimonials = Testimonial::active()
            ->ordered()
            ->take(6)
            ->get();
            
        $popularGames = Game::active()
            ->withCount('transactions')
            ->orderBy('transactions_count', 'desc')
            ->take(4)
            ->get();

        return view('home', compact('games', 'banners', 'testimonials', 'popularGames'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $games = Game::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->ordered()
            ->paginate(12);

        return view('games.search', compact('games', 'query'));
    }
}