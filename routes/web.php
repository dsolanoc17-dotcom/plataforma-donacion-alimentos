<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonacionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\OrganizacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return redirect()->route('donaciones.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // App resource routes
    Route::resource('donaciones', DonacionController::class);
    Route::resource('organizaciones', OrganizacionController::class)->only(['edit', 'update']);
    Route::resource('reservas', ReservaController::class)->only(['index', 'store', 'update']);
});

require __DIR__.'/auth.php';
