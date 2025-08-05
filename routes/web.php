<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LiberacaoProdutos;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard'); //->middleware(['auth', 'verified'])

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/liberacao.produto', [LiberacaoProdutos::class, 'index'])->name('liberacao.produtos.index');

require __DIR__.'/auth.php';
