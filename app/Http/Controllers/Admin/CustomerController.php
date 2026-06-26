<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Display the listing of all customers with transaction stats.
     */
    public function index()
    {
        $customers = User::where('role', User::ROLE_CUSTOMER)
            ->withCount('orders')
            ->withSum(['orders as total_spent' => function ($query) {
                $query->whereIn('payment_status', ['verified']);
            }], 'total')
            ->orderBy('created_at', 'desc')
            ->get();

        // Map total_spent to float
        $customers->transform(function ($customer) {
            $customer->total_spent = (float) ($customer->total_spent ?? 0);
            return $customer;
        });

        return Inertia::render('Admin/CustomerList', [
            'customers' => $customers,
        ]);
    }

    /**
     * Toggle the active status of a customer.
     */
    public function toggleStatus(User $user)
    {
        // Prevent toggling non-customer accounts
        if ($user->role !== User::ROLE_CUSTOMER) {
            return redirect()->back()->with('error', 'Aksi tidak valid untuk akun non-pelanggan.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun pelanggan {$user->name} berhasil {$status}.");
    }
}
