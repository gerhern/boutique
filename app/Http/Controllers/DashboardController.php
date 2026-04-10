<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Raffle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total     = Product::count();
        $available = Product::where('status', 'available')->count();
        $sold      = Product::where('status', 'sold')->count();
        $raffles   = Raffle::where('status', 'active')->count();

        $stats = [
            'total'         => $total,
            'available'     => $available,
            'available_pct' => $total > 0 ? round(($available / $total) * 100) : 0,
            'sold'          => $sold,
            'raffles'       => $raffles,
        ];

        $recentProducts = Product::with('category')
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact('stats', 'recentProducts'));
    }
}
