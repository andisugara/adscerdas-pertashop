<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OperatorController extends Controller
{
    public function index()
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        // Get operators for current organization
        $operators = $organization->users()
            ->wherePivot('role', 'operator')
            ->withPivot('is_active')
            ->latest()
            ->get();

        return view('operators.index', compact('operators', 'organization'));
    }

    public function create()
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        return view('operators.create', compact('organization'));
    }

    public function store(Request $request)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'operator',
            'active_organization_id' => $organization->id,
        ]);

        // Attach to organization with operator role
        $organization->users()->attach($user->id, [
            'role' => 'operator',
            'is_active' => true,
        ]);

        return redirect()->route('operators.index')
            ->with('success', 'Operator berhasil ditambahkan');
    }

    public function edit(User $operator)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        // Check if operator belongs to current organization
        if (!$organization->users()->where('users.id', $operator->id)->exists()) {
            abort(403, 'Operator tidak ditemukan di organisasi Anda');
        }

        return view('operators.edit', compact('operator', 'organization'));
    }

    public function update(Request $request, User $operator)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        // Check if operator belongs to current organization
        if (!$organization->users()->where('users.id', $operator->id)->exists()) {
            abort(403, 'Operator tidak ditemukan di organisasi Anda');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $operator->id,
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $operator->update($data);

        // Update pivot data
        if ($request->has('is_active')) {
            $organization->users()->updateExistingPivot($operator->id, [
                'is_active' => $request->boolean('is_active'),
            ]);
        }

        return redirect()->route('operators.index')
            ->with('success', 'Operator berhasil diupdate');
    }

    public function destroy(User $operator)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        // Check if operator belongs to current organization
        if (!$organization->users()->where('users.id', $operator->id)->exists()) {
            abort(403, 'Operator tidak ditemukan di organisasi Anda');
        }

        // Detach from organization
        $organization->users()->detach($operator->id);

        // If user has no other organizations, delete the user
        if ($operator->organizations()->count() === 0) {
            $operator->delete();
        }

        return redirect()->route('operators.index')
            ->with('success', 'Operator berhasil dihapus');
    }

    public function toggleStatus(User $operator)
    {
        $organization = Auth::user()->organization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Silakan pilih organisasi terlebih dahulu']);
        }

        // Check if operator belongs to current organization
        $pivot = $organization->users()->where('users.id', $operator->id)->first();

        if (!$pivot) {
            abort(403, 'Operator tidak ditemukan di organisasi Anda');
        }

        $currentStatus = $pivot->pivot->is_active;

        $organization->users()->updateExistingPivot($operator->id, [
            'is_active' => !$currentStatus,
        ]);

        return back()->with('success', 'Status operator berhasil diubah');
    }
}
