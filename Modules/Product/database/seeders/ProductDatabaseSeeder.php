<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;
use Modules\Product\Models\Product;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Wireless Bluetooth Headphones', 'category' => 'Electronics', 'price' => 79.99, 'stock' => 150],
            ['name' => 'Smart Fitness Watch', 'category' => 'Electronics', 'price' => 129.99, 'stock' => 100],
            ['name' => 'Stainless Steel Cookware Set', 'category' => 'Home & Kitchen', 'price' => 199.99, 'stock' => 60],
            ['name' => 'Ceramic Coffee Mug Set', 'category' => 'Home & Kitchen', 'price' => 24.99, 'stock' => 200],
            ['name' => "Men's Cotton T-Shirt", 'category' => 'Apparel', 'price' => 19.99, 'stock' => 300],
            ['name' => "Women's Running Shoes", 'category' => 'Apparel', 'price' => 89.99, 'stock' => 120],
            ['name' => 'Premium Yoga Mat', 'category' => 'Sports & Outdoors', 'price' => 34.99, 'stock' => 180],
            ['name' => 'Adjustable Dumbbell Set', 'category' => 'Sports & Outdoors', 'price' => 149.99, 'stock' => 50],
            ['name' => 'The Pragmatic Programmer', 'category' => 'Books', 'price' => 44.99, 'stock' => 80],
            ['name' => 'Atomic Habits', 'category' => 'Books', 'price' => 16.99, 'stock' => 250],
        ];

        foreach ($products as $index => $product) {
            $category = Category::where('slug', Str::slug($product['category']))->first();

            Product::firstOrCreate(
                ['sku' => sprintf('SKU-%04d', $index + 1)],
                [
                    'category_id' => $category?->id,
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'price' => $product['price'],
                    'stock_quantity' => $product['stock'],
                ]
            );
        }
    }
}
