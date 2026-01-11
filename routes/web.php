<?php

use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\NewsSourceController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('municipalities', MunicipalityController::class)->only(['index', 'show']);
Route::resource('news-sources', NewsSourceController::class)->only(['index', 'show']);

Route::prefix('dashboard/admin')->middleware(['auth', 'verified'])->name('dashboard.admin.')->group(function () {
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('municipalities', App\Http\Controllers\Admin\MunicipalityController::class);
    Route::resource('news-sources', App\Http\Controllers\Admin\NewsSourceController::class);
});

require __DIR__.'/settings.php';
