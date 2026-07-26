<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Category\Models\Category;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

class DashboardController extends Controller
{
    public function index(): View
    {
        $viewer = Auth::user();

        $canOrders = $viewer->can('orders.view');
        $canCustomers = $viewer->can('customers.view');

        $stats = [
            'products' => $viewer->can('products.view') ? Product::count() : null,
            'categories' => $viewer->can('categories.view') ? Category::count() : null,
            'orders' => $canOrders ? Order::count() : null,
            'revenue' => $canOrders ? Order::where('status', 'completed')->sum('total_amount') : null,
            'customers' => $canCustomers
                ? ($viewer->isEmployee() ? $viewer->assignedCustomers()->count() : User::where('role', 'customer')->count())
                : null,
        ];

        $orderStatusCounts = $canOrders
            ? Order::selectRaw('status, count(*) as aggregate')->groupBy('status')->pluck('aggregate', 'status')
            : collect();

        $recentOrders = $canOrders
            ? Order::with('user')->latest()->take(5)->get()
            : collect();

        return view('admin.dashboard', compact('stats', 'orderStatusCounts', 'recentOrders'));
    }
}
