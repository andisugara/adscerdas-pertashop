<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('organization')
            ->where('role', '!=', 'superadmin');

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by organization
        if ($request->filled('organization_id')) {
            $query->where('active_organization_id', $request->organization_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(25);
        $organizations = Organization::orderBy('name')->get();

        return view('superadmin.users.index', compact('users', 'organizations'));
    }

    public function show(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Cannot view superadmin details');
        }

        $user->load(['organization', 'organizations']);

        return view('superadmin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Cannot edit superadmin');
        }

        $organizations = Organization::orderBy('name')->get();

        return view('superadmin.users.edit', compact('user', 'organizations'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Cannot edit superadmin');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:owner,operator',
            'active_organization_id' => 'nullable|exists:organizations,id',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if ($validated['active_organization_id']) {
            $data['active_organization_id'] = $validated['active_organization_id'];
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.show', $user)
            ->with('success', 'User berhasil diupdate');
    }

    public function destroy(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Cannot delete superadmin');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Cannot toggle superadmin status');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back()->with('success', 'Status user berhasil diubah');
    }
}
