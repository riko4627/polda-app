<?php

use App\Http\Controllers\AssessmentScoreController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Master Positions & Weights
    Route::get('positions', [PositionController::class, 'index'])->name('positions.index');
    Route::post('positions', [PositionController::class, 'store'])->name('positions.store');
    Route::get('positions/{position}', [PositionController::class, 'show'])->name('positions.show');
    Route::put('positions/{position}', [PositionController::class, 'update'])->name('positions.update');
    Route::delete('positions/{position}', [PositionController::class, 'destroy'])->name('positions.destroy');
    Route::post('positions/{position}/weights', [PositionController::class, 'updateWeights'])->name('positions.weights');

    // Personnel
    Route::apiResource('personnel', PersonnelController::class);

    // Criteria
    Route::get('criteria', [CriterionController::class, 'index'])->name('criteria.index');
    Route::get('criteria/{criterion}', [CriterionController::class, 'show'])->name('criteria.show');

    // Assessment Scores
    Route::post('assessment-scores', [AssessmentScoreController::class, 'store'])->name('assessment-scores.store');

    // SPK TOPSIS Recommendations Engine
    Route::post('recommendations/run', [RecommendationController::class, 'run'])->name('recommendations.run');
    Route::get('recommendations/history', [RecommendationController::class, 'history'])->name('recommendations.history');
    Route::get('recommendations/{run}', [RecommendationController::class, 'show'])->name('recommendations.show');
});
