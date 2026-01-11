<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\MunicipalityType;
use App\Models\Province;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MunicipalityController extends Controller
{
    public function index(Request $request): Response
    {
        $municipalities = Municipality::query()
            ->with(['province', 'municipalityType'])
            ->withCount('newsSources')
            ->search($request->input('search'))
            ->ofProvince($request->input('province'))
            ->ofType($request->input('type'))
            ->orderBy('name')
            ->paginate(50);

        return Inertia::render('Municipalities/Index', [
            'municipalities' => $municipalities,
            'provinces' => Province::query()->orderBy('name')->get(),
            'types' => MunicipalityType::query()->orderBy('name')->get(),
        ]);
    }

    public function show(Municipality $municipality): Response
    {
        $municipality->load([
            'province',
            'municipalityType',
            'newsSources' => fn ($q) => $q->active()->orderBy('name'),
        ]);

        return Inertia::render('Municipalities/Show', [
            'municipality' => $municipality,
        ]);
    }
}
