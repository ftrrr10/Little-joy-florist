<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class OperatorController extends Controller
{
    /**
     * Display the listing of all operators.
     */
    public function index()
    {
        $operators = User::where('role', User::ROLE_OPERATOR)
            ->orderBy('created_at', 'desc')
            ->get();

        // Attach verified payments count manually
        $operators->transform(function ($operator) {
            $operator->verified_payments_count = Payment::where('verified_by', $operator->id)->count();
            return $operator;
        });

        return Inertia::render('Admin/OperatorList', [
            'operators' => $operators,
        ]);
    }

    /**
     * Store a newly created operator.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama operator wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'role' => User::ROLE_OPERATOR,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun operator baru berhasil didaftarkan.');
    }

    /**
     * Update the specified operator details.
     */
    public function update(Request $request, User $user)
    {
        // Prevent editing non-operator accounts
        if ($user->role !== User::ROLE_OPERATOR) {
            return redirect()->back()->with('error', 'Aksi tidak valid untuk akun non-operator.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama operator wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        $updateData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->input('password'));
        }

        $user->update($updateData);

        return redirect()->back()->with('success', "Data operator {$user->name} berhasil diperbarui.");
    }

    /**
     * Toggle the active status of an operator.
     */
    public function toggleStatus(User $user)
    {
        // Prevent toggling non-operator accounts
        if ($user->role !== User::ROLE_OPERATOR) {
            return redirect()->back()->with('error', 'Aksi tidak valid untuk akun non-operator.');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun operator {$user->name} berhasil {$status}.");
    }
}
