<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsSourceRequest;
use App\Http\Requests\UpdateNewsSourceRequest;
use App\Models\NewsSource;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class NewsSourceController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('news-sources.index');
    }

    public function create(): Response
    {
        return Inertia::render('admin/news-sources/Create');
    }

    public function store(StoreNewsSourceRequest $request): RedirectResponse
    {
        NewsSource::query()->create($request->validated());

        return redirect()->route('news-sources.index')
            ->with('success', 'News source created successfully.');
    }

    public function show(NewsSource $newsSource): RedirectResponse
    {
        return redirect()->route('news-sources.show', $newsSource);
    }

    public function edit(NewsSource $newsSource): Response
    {
        return Inertia::render('admin/news-sources/Edit', [
            'newsSource' => $newsSource,
        ]);
    }

    public function update(UpdateNewsSourceRequest $request, NewsSource $newsSource): RedirectResponse
    {
        $newsSource->update($request->validated());

        return redirect()->route('news-sources.show', $newsSource)
            ->with('success', 'News source updated successfully.');
    }

    public function destroy(NewsSource $newsSource): RedirectResponse
    {
        $newsSource->delete();

        return redirect()->route('news-sources.index')
            ->with('success', 'News source deleted successfully.');
    }
}
