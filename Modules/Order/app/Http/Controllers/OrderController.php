<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Order\Exceptions\InsufficientStockException;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;
use Modules\Product\Models\Product;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function index(): View
    {
        $orders = Order::with('user')->latest()->paginate(10);

        return view('order::index', compact('orders'));
    }

    public function create(): View
    {
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $products = Product::orderBy('name')->get();

        return view('order::create', compact('customers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['required', 'in:pending,processing,completed,cancelled'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['required', 'exists:products,id'],
            'quantities' => ['required', 'array', 'min:1'],
            'quantities.*' => ['required', 'integer', 'min:1'],
        ]);

        $customer = User::findOrFail($data['user_id']);

        $items = collect($data['products'])
            ->map(fn ($productId, $index) => [
                'product_id' => (int) $productId,
                'quantity' => (int) $data['quantities'][$index],
            ])
            ->values()
            ->all();

        try {
            $order = $this->orderService->createOrder($customer, $items, $data['status'], $data['notes'] ?? null);
        } catch (InsufficientStockException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('orders.show', $order)->with('success', 'Order created successfully.');
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('order::show', compact('order'));
    }

    public function destroy(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product?->increment('stock_quantity', $item->quantity);
            }

            $customer = $order->user;
            $order->delete();
            $customer?->refreshPurchaseStats();
        });

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
