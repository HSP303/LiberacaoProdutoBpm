<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('liberacao_produtos');
})->name('home');

Route::get('/liberacao-produtos', [App\Http\Controllers\LiberacaoProdutos::class, 'index'])
    ->name('liberacao.produtos.index');

Route::get('/pesquisa-empresa', [App\Http\Controllers\Empresa::class, 'pesquisar'])->name('empresa.pesquisar');