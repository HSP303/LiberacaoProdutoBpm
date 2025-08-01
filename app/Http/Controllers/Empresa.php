<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Empresa extends Controller
{
    public function pesquisar(Request $request)
    {
        $q = $request->get('q');

        $resultados = DB::table('papel')
            ->select('id', 'nome')
            ->where('nome', 'like', '%' . $q . '%')
            ->limit(10)
            ->get();

        return response()->json($resultados);
    }
}
