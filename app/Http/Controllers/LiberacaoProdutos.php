<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiberacaoProdutos extends Controller
{
    public function index()
    {
        return view('liberacao_produtos');
    }
}
