<?php

namespace Modules\Category\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Category\Models\Category;

class CategoryDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Electronics', 'Home & Kitchen', 'Apparel', 'Sports & Outdoors', 'Books'];

        foreach ($names as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
