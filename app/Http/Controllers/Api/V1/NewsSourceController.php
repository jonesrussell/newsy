<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsSourceRequest;
use App\Http\Requests\UpdateNewsSourceRequest;
use App\Http\Resources\NewsSourceResource;
use App\Models\NewsSource;
use Illuminate\Http\Request;

class NewsSourceController extends Controller
{
    public function index(Request $request)
    {
        $newsSources = NewsSource::query()
            ->withCount('municipalities')
            ->when($request->input('active_only'), fn ($q) => $q->active())
            ->ofType($request->input('type'))
            ->ofScope($request->input('scope'))
            ->orderBy('name')
            ->paginate($request->input('per_page', 50));

        return NewsSourceResource::collection($newsSources);
    }

    public function show(NewsSource $newsSource)
    {
        $newsSource->load('municipalities');

        return new NewsSourceResource($newsSource);
    }

    public function store(StoreNewsSourceRequest $request)
    {
        $newsSource = NewsSource::query()->create($request->validated());

        return new NewsSourceResource($newsSource);
    }

    public function update(UpdateNewsSourceRequest $request, NewsSource $newsSource)
    {
        $newsSource->update($request->validated());

        return new NewsSourceResource($newsSource);
    }

    public function destroy(NewsSource $newsSource)
    {
        $newsSource->delete();

        return response()->noContent();
    }
}
