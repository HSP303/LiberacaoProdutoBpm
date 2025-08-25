<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnexosLiberacao extends Controller
{
    public function show($id)
    {
        // Verifica se ID existe na tabela de liberações
        $liberacao = Liberacao::find($id);

        if (!$liberacao) {
            abort(404, 'ID de liberação não encontrado.');
        }

        return view('files', compact('liberacao'));
    }
}
