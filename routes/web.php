<?php

use App\Http\Controllers\CavidadeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LiberacaoProdutos;
use App\Http\Controllers\ItemLiberacaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::get('/home', [LiberacaoProdutos::class, 'index'])->name('dashboard.index');

//INSERIR REGISTROS LIBERACAO PRODUTOS
Route::post('/liberacao-produtos', [LiberacaoProdutos::class, 'store'])->name('liberacao-produtos.store');
Route::put('/liberacao-produtos/{id}', [LiberacaoProdutos::class, 'update'])->name('liberacao-produtos.update');
Route::get('/liberacoes/ids', [LiberacaoProdutos::class, 'getIds'])->name('liberacoes.ids');
Route::post('/buscar-produtos', [LiberacaoProdutos::class, 'buscarProdutos'])->name('buscar.produtos');


// INSERIR ITENS DA LIBERAÇÃO 
Route::post('/itens-liberacao', [ItemLiberacaoController::class, 'store'])->name('itens-liberacao.store');
Route::delete('/itens-liberacao-delete', [ItemLiberacaoController::class, 'delete'])->name('itens-liberacao.delete');
Route::put('/itens-liberacao-put', [ItemLiberacaoController::class, 'update'])->name('itens-liberacao.update');
Route::post('/item-liberacao/update-minmax', [ItemLiberacaoController::class, 'updateMinMax'])->name('item-liberacao.update-minmax');

// INSERIR CAVIDADES
Route::post('/cavidades-liberacao', [CavidadeController::class, 'store'])->name('cavidades-liberacao.store');


Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified']) ->name('dashboard');

Route::get('/welcome', [LoginController::class, 'store'])->name('login');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
