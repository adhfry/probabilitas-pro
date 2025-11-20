<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\AnalysisController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('dashboard');

Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

Route::get('/workspace/{project}', [WorkspaceController::class, 'show'])->name('workspace.show');
Route::patch('/workspace/{project}/attributes/{attribute}', [WorkspaceController::class, 'updateAttribute'])->name('workspace.attributes.update');
Route::patch('/workspace/{project}/classes/{class}', [WorkspaceController::class, 'updateClass'])->name('workspace.classes.update');
Route::post('/workspace/{project}/attributes', [WorkspaceController::class, 'addAttribute'])->name('workspace.attributes.add');
Route::post('/workspace/{project}/classes', [WorkspaceController::class, 'addClass'])->name('workspace.classes.add');
Route::post('/workspace/{project}/training-data', [WorkspaceController::class, 'updateTrainingData'])->name('workspace.training-data.update');

Route::post('/analysis/{project}', [AnalysisController::class, 'analyze'])->name('analysis.analyze');

require __DIR__.'/auth.php';
