<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalRevenue = Order::paid()->sum('grand_total');
        $totalOrders = Order::count();
        $paidOrdersCount = Order::paid()->count();
        $pendingOrdersCount = Order::pending()->count();
        $totalCustomers = User::whereHas('roles', fn ($q) => $q->where('slug', 'customer'))->count();
        $totalProducts = Product::count();
        $lowStockCount = Product::where('stock', '<=', 5)->count();

        $averageOrderValue = $paidOrdersCount > 0 ? ($totalRevenue / $paidOrdersCount) : 0;

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        $topProducts = OrderItem::select('product_id', 'product_name', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(total_price) as total_revenue'))
            ->whereNotNull('product_id')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'paidOrdersCount',
            'pendingOrdersCount',
            'totalCustomers',
            'totalProducts',
            'lowStockCount',
            'averageOrderValue',
            'recentOrders',
            'topProducts'
        ));
    }
}
