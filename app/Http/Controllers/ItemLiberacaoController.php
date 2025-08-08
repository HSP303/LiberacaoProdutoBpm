<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemLiberacao;

class ItemLiberacaoController extends Controller
{
    public function store(Request $request)
    {
        $idItem = $request->id;

        $request->validate([
            'id' => 'required|exists:liberacao_produtos,id',
            'id_item' => 'required|string|max:255',
            'especificado' => 'required|string|max:255',
            'equipamento' => 'required|string|max:255',
            'resultado' => 'nullable|string|max:255',
        ]);

        ItemLiberacao::create([
            'id' => $request->id,
            'id_item' => $request->id_item,
            'especificado' => $request->especificado,
            'equipamento' => $request->equipamento,
            'resultado' => $request->resultado, // 'OK' ou 'Não OK'
        ]);

        return redirect()->route('dashboard.index', ['id' => $idItem, 'code' => 201])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 201]);
    }
}
