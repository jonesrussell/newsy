<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttachNewsSourceRequest;
use App\Http\Resources\NewsSourceResource;
use App\Models\Municipality;
use Illuminate\Http\Request;

class MunicipalityNewsSourceController extends Controller
{
    public function index(Municipality $municipality, Request $request)
    {
        $newsSources = $municipality->newsSources()
            ->ofType($request->input('type'))
            ->ofScope($request->input('scope'))
            ->get();

        return NewsSourceResource::collection($newsSources);
    }

    public function store(AttachNewsSourceRequest $request, Municipality $municipality)
    {
        $municipality->newsSources()->attach(
            $request->input('news_source_id'),
            [
                'coverage_type' => $request->input('coverage_type'),
                'notes' => $request->input('notes'),
                'verified_at' => now(),
            ]
        );

        return response()->json(['message' => 'News source attached successfully.'], 201);
    }

    public function destroy(Municipality $municipality, int $newsSourceId)
    {
        $municipality->newsSources()->detach($newsSourceId);

        return response()->noContent();
    }
}
