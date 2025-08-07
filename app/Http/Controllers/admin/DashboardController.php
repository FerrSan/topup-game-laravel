<?php 
// 6. Admin/DashboardController
// app/Http/Controllers/Admin/DashboardController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $statistics = [
            'total_transactions' => Transaction::count(),
            'total_revenue' => Transaction::success()->sum('total_amount'),
            'total_users' => User::where('is_admin', false)->count(),
            'total_games' => Game::active()->count(),
            'today_transactions' => Transaction::today()->count(),
            'today_revenue' => Transaction::today()->success()->sum('total_amount'),
            'pending_transactions' => Transaction::pending()->count(),
            'this_month_revenue' => Transaction::thisMonth()->success()->sum('total_amount'),
        ];
        
        // Recent transactions
        $recentTransactions = Transaction::with(['game', 'product', 'user'])
            ->latest()
            ->take(10)
            ->get();
            
        // Top selling games
        $topGames = Game::withCount(['transactions' => function($query) {
                $query->success();
            }])
            ->orderBy('transactions_count', 'desc')
            ->take(5)
            ->get();
            
        // Revenue chart data (last 7 days)
        $chartData = $this->getRevenueChartData();
        
        return view('admin.dashboard', compact('statistics', 'recentTransactions', 'topGames', 'chartData'));
    }

    protected function getRevenueChartData()
    {
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $revenue = Transaction::whereDate('created_at', $date)
                ->success()
                ->sum('total_amount');
                
            $data['labels'][] = $date->format('d M');
            $data['values'][] = $revenue;
        }
        
        return $data;
    }
}

