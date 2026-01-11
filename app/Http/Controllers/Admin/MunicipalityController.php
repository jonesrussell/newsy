<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMunicipalityRequest;
use App\Http\Requests\Admin\UpdateMunicipalityRequest;
use App\Models\Municipality;
use App\Models\MunicipalityType;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class MunicipalityController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('municipalities.index');
    }

    public function create(): Response
    {
        return Inertia::render('admin/municipalities/Create', [
            'provinces' => Province::query()->orderBy('name')->get(),
            'types' => MunicipalityType::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreMunicipalityRequest $request): RedirectResponse
    {
        Municipality::query()->create($request->validated());

        return redirect()->route('municipalities.index')
            ->with('success', 'Municipality created successfully.');
    }

    public function show(Municipality $municipality): RedirectResponse
    {
        return redirect()->route('municipalities.show', $municipality);
    }

    public function edit(Municipality $municipality): Response
    {
        $municipality->load(['province', 'municipalityType']);

        return Inertia::render('admin/municipalities/Edit', [
            'municipality' => $municipality,
            'provinces' => Province::query()->orderBy('name')->get(),
            'types' => MunicipalityType::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateMunicipalityRequest $request, Municipality $municipality): RedirectResponse
    {
        $municipality->update($request->validated());

        return redirect()->route('municipalities.show', $municipality)
            ->with('success', 'Municipality updated successfully.');
    }

    public function destroy(Municipality $municipality): RedirectResponse
    {
        $municipality->delete();

        return redirect()->route('municipalities.index')
            ->with('success', 'Municipality deleted successfully.');
    }
}
