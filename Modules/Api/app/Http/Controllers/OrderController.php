<?php

namespace Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Order\Exceptions\InsufficientStockException;
use Modules\Order\Models\Order;
use Modules\Order\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * The authenticated customer's own order history.
     */
    public function index(Request $request): JsonResponse
    {
        $orders = $request->user()->orders()->with('items.product')->latest()->paginate(10);

        return response()->json([
            'data' => $orders->map($this->transform(...))->all(),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Place an order — either as an authenticated customer (Bearer token) or
     * as a guest (name + email in the payload, matched or created by email).
     * On success, stock is decremented and an invoice is emailed automatically
     * (see OrderService).
     */
    public function store(Request $request): JsonResponse
    {
        $customer = Auth::guard('sanctum')->user();

        $rules = [
            'notes' => ['nullable', 'string', 'max:1000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];

        if (! $customer) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255'];
        }

        $data = $request->validate($rules);

        if (! $customer) {
            $customer = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make(Str::random(40)),
                    'role' => 'customer',
                    'status' => 'active',
                ]
            );
        }

        try {
            $order = $this->orderService->createOrder(
                $customer,
                $data['items'],
                'completed',
                $data['notes'] ?? null,
            );
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $order->load(['items.product']);

        return response()->json(['data' => $this->transform($order)], 201);
    }

    private function transform(Order $order): array
    {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'total_amount' => (float) $order->total_amount,
            'notes' => $order->notes,
            'created_at' => $order->created_at->toIso8601String(),
            'items' => $order->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'product_name' => $item->product->name ?? null,
                'quantity' => $item->quantity,
                'unit_price' => (float) $item->unit_price,
                'subtotal' => (float) $item->subtotal,
            ])->all(),
        ];
    }
}
