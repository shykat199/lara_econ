<?php

namespace Modules\Order\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Order\Services\OrderService;
use Modules\Product\Models\Product;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Each order ("sale") is created via OrderService so stock decrements,
     * purchase stats, and order numbering stay consistent with orders
     * placed through the app; its line items are the "transactions".
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'completed', 'completed', 'cancelled'];
        $orderService = app(OrderService::class);

        for ($i = 0; $i < 10; $i++) {
            $items = $products->random(min(3, $products->count()))
                ->map(fn (Product $product) => [
                    'product_id' => $product->id,
                    'quantity' => rand(1, 3),
                ])
                ->values()
                ->all();

            $order = $orderService->createOrder($customers->random(), $items, Arr::random($statuses));

            $placedAt = now()->subDays(rand(0, 45))->subHours(rand(0, 23));
            $order->forceFill(['created_at' => $placedAt, 'updated_at' => $placedAt])->save();
        }
    }
}
