<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreOrganisationRequest;
use App\Models\Organisation;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OrganisationController
{
    /**
     * Show organisations list
     */
    public function index(): View 
    {
        $organisations = Organisation::withCount('users')->paginate(20);
        return view('admin.organisations.index', compact('organisations'));
    }

    /**
     * Show create organisation form
     */
    public function create(): View
    {
        return view('admin.organisations.create');
    }

    /**
     * Store new organisation
     */
    public function store(StoreOrganisationRequest $request): RedirectResponse
    {
        $organisation = Organisation::create($request->validated());

        return redirect()
            ->route('admin.organisations.edit', $organisation->id)
            ->with('success', 'Organisation created successfully.');
    }

    /**
     * Show edit organisation form
     */
    public function edit(int $id): View
    {
        $organisation = Organisation::findOrFail($id);
        return view('admin.organisations.edit', compact('organisation'));
    }

    /**
     * Update organisation
     */
    public function update(int $id): RedirectResponse
    {
        $organisation = Organisation::findOrFail($id);

        $validated = request()->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'logo_path' => 'nullable|string',
            'country' => 'nullable|string',
            'supported_languages' => 'nullable|json',
            'modules_enabled' => 'nullable|json',
        ]);

        $organisation->update($validated);

        return back()->with('success', 'Organisation updated.');
    }
}
