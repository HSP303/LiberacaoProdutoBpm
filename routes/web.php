<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/liberacao-produtos', [App\Http\Controllers\LiberacaoProdutos::class, 'index'])
    ->name('liberacao.produtos.index');