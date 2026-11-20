<?php

use App\Http\Controllers\PlantController;
use App\Http\Controllers\PlantReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Every plant and report page requires an authenticated administrator.
// Breeze already redirects guests to the login page.
Route::middleware('auth')->group(function () {
    // Named resource routes: plants.index, plants.create, plants.store,
    // plants.show, plants.edit, plants.update, plants.destroy.
    Route::resource('plants', PlantController::class);

    // Plant reports: the same three endpoints power the HTML report and
    // both filtered exports.
    Route::get('/reports', [PlantReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [PlantReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('/reports/excel', [PlantReportController::class, 'excel'])->name('reports.excel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
