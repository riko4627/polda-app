<?php

use App\Http\Controllers\Web\CriterionWebController;
use App\Http\Controllers\Web\PersonnelWebController;
use App\Http\Controllers\Web\PositionWebController;
use App\Http\Controllers\Web\RecommendationWebController;
use App\Models\Criterion;
use App\Models\Personnel;
use App\Models\Position;
use App\Models\TopsisRun;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $totalPersonnel = Personnel::query()->where('status', 'active')->count();
    $totalPositions = Position::query()->where('is_active', true)->count();
    $totalCriteria = Criterion::query()->where('is_active', true)->count();
    $totalRuns = TopsisRun::query()->count();

    $positions = Position::query()
        ->where('is_active', true)
        ->with(['rule.requiredRank', 'rule.minRank'])
        ->get();

    return view('home', compact(
        'totalPersonnel',
        'totalPositions',
        'totalCriteria',
        'totalRuns',
        'positions'
    ));
})->name('home');

// Modul 1: Manajemen Personel
Route::prefix('personnel')->name('web.personnel.')->group(function (): void {
    Route::get('/', [PersonnelWebController::class, 'index'])->name('index');
    Route::get('/create', [PersonnelWebController::class, 'create'])->name('create');
    Route::post('/', [PersonnelWebController::class, 'store'])->name('store');
    Route::get('/{personnel}', [PersonnelWebController::class, 'show'])->name('show');
    Route::get('/{personnel}/edit', [PersonnelWebController::class, 'edit'])->name('edit');
    Route::put('/{personnel}', [PersonnelWebController::class, 'update'])->name('update');
    Route::delete('/{personnel}', [PersonnelWebController::class, 'destroy'])->name('destroy');
    Route::post('/{personnel}/scores', [PersonnelWebController::class, 'storeScores'])->name('scores');
    Route::post('/{personnel}/histories', [PersonnelWebController::class, 'storeHistory'])->name('histories');
    Route::post('/{personnel}/certifications', [PersonnelWebController::class, 'storeCertification'])->name('certifications');
});

// Modul 2: Manajemen Jabatan, Syarat Mutlak, dan Bobot Dinamis
Route::prefix('positions')->name('web.positions.')->group(function (): void {
    Route::get('/', [PositionWebController::class, 'index'])->name('index');
    Route::get('/create', [PositionWebController::class, 'create'])->name('create');
    Route::post('/', [PositionWebController::class, 'store'])->name('store');
    Route::get('/{position}', [PositionWebController::class, 'show'])->name('show');
    Route::get('/{position}/edit', [PositionWebController::class, 'edit'])->name('edit');
    Route::put('/{position}', [PositionWebController::class, 'update'])->name('update');
    Route::delete('/{position}', [PositionWebController::class, 'destroy'])->name('destroy');
    Route::get('/{position}/weights', [PositionWebController::class, 'weights'])->name('weights');
    Route::post('/{position}/weights', [PositionWebController::class, 'updateWeights'])->name('weights.update');
});

// Modul 3: Master Kriteria Penilaian
Route::prefix('criteria')->name('web.criteria.')->group(function (): void {
    Route::get('/', [CriterionWebController::class, 'index'])->name('index');
    Route::post('/', [CriterionWebController::class, 'store'])->name('store');
    Route::put('/{criterion}', [CriterionWebController::class, 'update'])->name('update');
    Route::delete('/{criterion}', [CriterionWebController::class, 'destroy'])->name('destroy');
});

// Modul 4: Seleksi Rekomendasi SPK-TOPSIS & Sidang Wanjak
Route::prefix('recommendations')->name('web.recommendations.')->group(function (): void {
    Route::get('/', [RecommendationWebController::class, 'index'])->name('index');
    Route::post('/run', [RecommendationWebController::class, 'run'])->name('run');
    Route::get('/history', [RecommendationWebController::class, 'history'])->name('history');
    Route::get('/{id}', [RecommendationWebController::class, 'show'])->name('show');
});
