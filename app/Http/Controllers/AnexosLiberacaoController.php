<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use Illuminate\Http\Request;

class AnexosLiberacaoController extends Controller
{
    public function show($id)
    {
        // Verifica se ID existe na tabela de liberações
        $anexos = AnexosLiberacao::find($id);

        if (!$anexos) {
            abort(404, 'ID  de liberação não encontrado.');
        }

        return view('files', compact('anexos'));
    }

    public function destroy($id, $id_arq){

    }
}
