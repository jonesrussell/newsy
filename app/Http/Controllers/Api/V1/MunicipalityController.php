<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MunicipalityResource;
use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalityController extends Controller
{
    public function index(Request $request)
    {
        $municipalities = Municipality::query()
            ->with(['province', 'municipalityType'])
            ->withCount('newsSources')
            ->search($request->input('search'))
            ->ofProvince($request->input('province'))
            ->ofType($request->input('type'))
            ->orderBy('name')
            ->paginate($request->input('per_page', 50));

        return MunicipalityResource::collection($municipalities);
    }

    public function show(Municipality $municipality)
    {
        $municipality->load(['province', 'municipalityType', 'newsSources']);

        return new MunicipalityResource($municipality);
    }
}
