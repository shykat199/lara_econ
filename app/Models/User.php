<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Modules\Customer\Models\CustomerContact;
use Modules\Order\Models\Order;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'avatar', 'role', 'status', 'phone', 'address', 'assigned_employee_id', 'assigned_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * Keep the Spatie role in sync with the domain `role` column, so the two
     * never drift apart: `role` drives business logic (login gate, customer
     * detection, ...), the synced Spatie role drives fine-grained permissions.
     */
    protected static function booted(): void
    {
        static::saved(function (User $user) {
            if ($user->wasChanged('role') || $user->roles()->count() === 0) {
                $user->syncRoles([$user->role]);
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'assigned_at' => 'datetime',
            'last_purchase_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_employee_id');
    }

    public function assignedCustomers(): HasMany
    {
        return $this->hasMany(User::class, 'assigned_employee_id');
    }

    public function contactLogs(): HasMany
    {
        return $this->hasMany(CustomerContact::class)->latest();
    }

    /**
     * A customer is "lost" if they've never purchased (and their account is older
     * than the window) or their last purchase falls outside the given window.
     */
    public function isLost(int $days = 90): bool
    {
        if (! $this->isCustomer()) {
            return false;
        }

        $cutoff = Carbon::now()->subDays($days);

        return $this->last_purchase_at
            ? $this->last_purchase_at->lt($cutoff)
            : $this->created_at->lt($cutoff);
    }

    /**
     * Record a completed purchase: update purchase stats and, if this customer
     * was assigned to an employee for follow-up, credit that employee's KPI
     * score and close out the assignment.
     */
    public function recordPurchase(): void
    {
        $this->forceFill([
            'last_purchase_at' => now(),
            'purchase_count' => $this->purchase_count + 1,
        ])->save();

        if ($this->assigned_employee_id) {
            User::whereKey($this->assigned_employee_id)->increment('kpi_score');

            $this->forceFill([
                'assigned_employee_id' => null,
                'assigned_at' => null,
            ])->save();
        }
    }

    /**
     * Recompute cached purchase stats from the orders table (e.g. after an order is deleted).
     */
    public function refreshPurchaseStats(): void
    {
        $this->forceFill([
            'purchase_count' => $this->orders()->count(),
            'last_purchase_at' => $this->orders()->max('created_at'),
        ])->save();
    }
}
