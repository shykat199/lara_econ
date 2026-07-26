<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\Acl\Database\Seeders\AclDatabaseSeeder;
use Modules\Category\Database\Seeders\CategoryDatabaseSeeder;
use Modules\Customer\Database\Seeders\CustomerDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AclDatabaseSeeder::class,
            UserDatabaseSeeder::class,
            CategoryDatabaseSeeder::class,
            ProductDatabaseSeeder::class,
            CustomerDatabaseSeeder::class,
            OrderDatabaseSeeder::class,
        ]);

        // Model events are needed here to sync each user's Spatie role, so this
        // runs outside of WithoutModelEvents (which this seeder intentionally
        // does not use).
        User::all()->each(fn (User $user) => $user->syncRoles([$user->role]));
    }
}
