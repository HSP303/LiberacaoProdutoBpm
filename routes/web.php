<?php

use App\Http\Controllers\LiberacaoProdutos;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('liberacao_produtos');})->name('liberacao_produtos');

Route::get('/liberacao.produtos', [LiberacaoProdutos::class, 'index'])->name('liberacao.produtos.index');
