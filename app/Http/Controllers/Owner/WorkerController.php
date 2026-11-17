<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = User::where('role', 'worker')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('owner.workers.index', compact('workers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['required', 'boolean'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'worker',
            'is_active' => $validated['is_active'],
        ]);

        return redirect()->route('owner.workers.index')
            ->with('success', 'Akun worker berhasil dibuat.');
    }

    public function toggleStatus(User $worker)
    {
        $worker->update([
            'is_active' => !$worker->is_active,
        ]);

        return redirect()->route('owner.workers.index')
            ->with('success', 'Status akun worker berhasil diperbarui.');
    }

    public function destroy(User $worker)
    {
        $worker->delete();

        return redirect()->route('owner.workers.index')
            ->with('success', 'Akun worker berhasil dihapus.');
    }
}