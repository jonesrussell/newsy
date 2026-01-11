<?php

namespace App\Http\Controllers;

use App\Models\NewsSource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsSourceController extends Controller
{
    public function index(Request $request): Response
    {
        $newsSources = NewsSource::query()
            ->withCount('municipalities')
            ->when($request->input('search'), fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->input('type'), fn ($q, $type) => $q->where('type', $type))
            ->when($request->input('scope'), fn ($q, $scope) => $q->where('scope', $scope))
            ->when($request->input('language'), fn ($q, $language) => $q->where('language', $language))
            ->when($request->boolean('active'), fn ($q) => $q->active())
            ->orderBy('name')
            ->paginate(50);

        return Inertia::render('news-sources/Index', [
            'newsSources' => $newsSources,
            'filters' => $request->only(['search', 'type', 'scope', 'language', 'active']),
        ]);
    }

    public function show(NewsSource $newsSource): Response
    {
        $newsSource->load([
            'municipalities' => fn ($q) => $q->with(['province', 'municipalityType'])->orderBy('name'),
        ]);

        return Inertia::render('news-sources/Show', [
            'newsSource' => $newsSource,
        ]);
    }
}
