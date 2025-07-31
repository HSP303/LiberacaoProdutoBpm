<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiberacaoProdutos extends Controller
{
    public function index()
    {
        // Your logic for listing products
        return view('liberacao_produtos');
    }
}
