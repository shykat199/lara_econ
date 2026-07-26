<?php

namespace Modules\Order\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Order\Emails\OrderInvoiceMail;
use Modules\Order\Exceptions\InsufficientStockException;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;
use Modules\Product\Models\Product;
use Throwable;

class OrderService
{
    /**
     * Create an order with its line items, decrementing product stock and
     * updating the customer's purchase stats, then email them an invoice.
     *
     * @param  array<int, array{product_id: int, quantity: int}>  $items
     *
     * @throws InsufficientStockException
     */
    public function createOrder(User $customer, array $items, string $status = 'completed', ?string $notes = null): Order
    {
        $order = DB::transaction(function () use ($customer, $items, $status, $notes) {
            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'ORD-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'status' => $status,
                'notes' => $notes,
                'total_amount' => 0,
            ]);

            $total = 0;

            foreach ($items as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                $quantity = (int) $item['quantity'];

                if ($quantity > $product->stock_quantity) {
                    throw new InsufficientStockException(
                        "Not enough stock for \"{$product->name}\" (requested {$quantity}, available {$product->stock_quantity})."
                    );
                }

                $subtotal = $product->price * $quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock_quantity', $quantity);
            }

            $order->update(['total_amount' => $total]);

            if ($status !== 'cancelled') {
                $customer->recordPurchase();
            }

            return $order;
        });

        if ($order->status !== 'cancelled') {
            $this->sendInvoice($order);
        }

        return $order;
    }

    /**
     * Email the customer their invoice. Failures are logged, not thrown —
     * the order itself has already been saved and shouldn't be rolled back
     * over a mail delivery problem.
     */
    private function sendInvoice(Order $order): void
    {
        try {
            $order->loadMissing(['user', 'items.product']);
            Mail::to($order->user->email)->send(new OrderInvoiceMail($order));
        } catch (Throwable $e) {
            Log::error("Failed to send invoice for order {$order->order_number}: {$e->getMessage()}");
        }
    }
}
