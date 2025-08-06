<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LiberacaoProdutos;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

//Route::get('/', [LoginController::class, 'store'])->name('welcome');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//Route::get('/', [LiberacaoProdutos::class, 'index'])->name('welcome');

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']) ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
