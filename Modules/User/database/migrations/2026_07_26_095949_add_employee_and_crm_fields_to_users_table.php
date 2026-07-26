<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Widen the role enum to add "employee" (raw SQL avoids needing doctrine/dbal for ->change()).
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer', 'employee') NOT NULL DEFAULT 'customer'");

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('assigned_employee_id')->nullable()->after('address')
                ->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable()->after('assigned_employee_id');
            $table->unsignedInteger('kpi_score')->default(0)->after('assigned_at');
            $table->timestamp('last_purchase_at')->nullable()->after('kpi_score');
            $table->unsignedInteger('purchase_count')->default(0)->after('last_purchase_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_employee_id');
            $table->dropColumn(['assigned_at', 'kpi_score', 'last_purchase_at', 'purchase_count']);
        });

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'customer') NOT NULL DEFAULT 'customer'");
    }
};
