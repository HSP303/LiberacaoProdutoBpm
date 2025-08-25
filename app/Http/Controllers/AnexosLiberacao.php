<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use Illuminate\Http\Request;

class AnexosLiberacao extends Controller
{
    public function show($id)
    {
        // Verifica se ID existe na tabela de liberações
        $anexos = LiberacaoProduto::find($id);

        if (!$anexos) {
            abort(404, 'ID de liberação não encontrado.');
        }

        return view('files', compact('anexos'));
    }

    public function destroy($id, $id_arq){
        
    }
}
