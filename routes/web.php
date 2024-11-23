<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Ficha;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/fichaCreate', [Ficha::class, 'store'])->name('ficha.store'); // Criar Ficha
Route::get('/ficha/{statusId}', [Ficha::class, 'list'])->name('ficha.list'); // Listar fichas
Route::get('/fichas', function () { return Inertia::render('Ficha');})->middleware(['auth', 'verified'])->name('fichas');
Route::get('/fichaView/{statusId}', [Ficha::class, 'view'])->name('ficha.view'); // Ver Ficha
Route::post('/fichaUpdate', [Ficha::class, 'update'])->name('ficha.update'); // Atualizar Ficha

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
