<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Organization::withCount('users', 'subscriptions')
            ->latest()
            ->paginate(20);

        return view('superadmin.organizations.index', compact('organizations'));
    }

    public function show(Organization $organization)
    {
        $organization->load('users', 'subscriptions', 'settings');
        return view('superadmin.organizations.show', compact('organization'));
    }

    // Other methods can be implemented later
    public function create()
    {
        return 'Coming soon';
    }
    public function store(Request $request)
    {
        return 'Coming soon';
    }
    public function edit(Organization $organization)
    {
        return 'Coming soon';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
