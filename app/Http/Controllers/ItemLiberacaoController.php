<?php

namespace App\Http\Controllers;

use App\Models\CavidadeLiberacao;
use Illuminate\Http\Request;
use App\Models\ItemLiberacao;
use App\Models\ItemCavidadeLiberacao;

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

    // ✅ Novo método para atualizar mínimo e máximo via AJAX
    public function updateMinMax(Request $request)
    {   
        \Log::info($request->all()); // Loga os dados recebidos

        $request->validate([
            'id' => 'required|exists:item_liberacao,id',
            'minimo' => 'nullable|numeric',
            'maximo' => 'nullable|numeric',
        ]);

        $item = ItemLiberacao::find($request->id);

        if ($item) {
            $item->minimo = $request->minimo;
            $item->maximo = $request->maximo;
            $item->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Valores atualizados com sucesso!',
                'status_code' => 200
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Item não encontrado!',
            'status_code' => 404
        ], 404);
    }

    public function delete(Request $request){
        $idLib = $request->id;
        $idItem = $request->id_item;

        ItemCavidadeLiberacao::where('id', $idLib)
            ->where('id_item', $idItem)
            ->delete();

        ItemLiberacao::where('id', $idLib)
            ->where('id_item', $idItem)
            ->delete();

        return redirect()->route('dashboard.index', ['id' => $idLib, 'code' => 205])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 205]);
    }
}
