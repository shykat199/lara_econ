<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Customer\Models\CustomerContact;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $days = (int) $request->integer('days', 90) ?: 90;
        $lostOnly = $request->boolean('lost');
        $viewer = Auth::user();

        if ($viewer->isEmployee()) {
            $customers = $viewer->assignedCustomers()->orderBy('assigned_at')->paginate(10);
        } else {
            $query = User::where('role', 'customer');

            if ($lostOnly) {
                $cutoff = now()->subDays($days);
                $query->where(function ($q) use ($cutoff) {
                    $q->whereNull('last_purchase_at')->where('created_at', '<', $cutoff)
                        ->orWhere('last_purchase_at', '<', $cutoff);
                });
            }

            $customers = $query->orderByDesc('last_purchase_at')->paginate(10)->withQueryString();
        }

        $employees = User::where('role', 'employee')->where('status', 'active')->orderBy('name')->get();

        return view('customer::index', compact('customers', 'employees', 'days', 'lostOnly'));
    }

    public function show(Request $request, User $customer): View
    {
        $this->authorizeAccess($customer);

        $days = (int) $request->integer('days', 90) ?: 90;
        $customer->load(['orders.items.product', 'contactLogs.sender', 'assignedEmployee']);
        $employees = User::where('role', 'employee')->where('status', 'active')->orderBy('name')->get();

        return view('customer::show', compact('customer', 'employees', 'days'));
    }

    public function assign(Request $request, User $customer): RedirectResponse
    {
        abort_unless(Auth::user()->can('customers.assign'), 403);

        $data = $request->validate([
            'employee_id' => ['required', 'exists:users,id'],
        ]);

        $customer->update([
            'assigned_employee_id' => $data['employee_id'],
            'assigned_at' => now(),
        ]);

        return back()->with('success', "{$customer->name} has been assigned for follow-up.");
    }

    public function unassign(User $customer): RedirectResponse
    {
        abort_unless(Auth::user()->can('customers.assign'), 403);

        $customer->update([
            'assigned_employee_id' => null,
            'assigned_at' => null,
        ]);

        return back()->with('success', "{$customer->name} has been unassigned.");
    }

    public function reengage(Request $request, User $customer): RedirectResponse
    {
        $this->authorizeAccess($customer);

        $data = $request->validate([
            'channel' => ['required', 'in:email,sms'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        CustomerContact::create([
            'user_id' => $customer->id,
            'sent_by' => Auth::id(),
            'channel' => $data['channel'],
            'message' => $data['message'],
        ]);

        $label = $data['channel'] === 'email' ? 'Email' : 'SMS';

        return back()->with('success', "{$label} to {$customer->name} sent (simulated).");
    }

    private function authorizeAccess(User $customer): void
    {
        $viewer = Auth::user();

        abort_unless(
            $viewer->isAdmin() || $customer->assigned_employee_id === $viewer->id,
            403
        );
    }
}
