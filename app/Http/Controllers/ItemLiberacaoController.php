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

        $cavidadesLiberacao = CavidadeLiberacao::where('id', $idItem)->get();

        foreach ($cavidadesLiberacao as $cavidade) {
            ItemCavidadeLiberacao::create([
                'id' => $idItem,
                'id_item' => $request->id_item, // Pega o id_item de cada registro
                'id_cavidade' => $cavidade->id_cavidade,
                'minimo' => 0,
                'maximo' => 0,
            ]);
        }

        return redirect()->route('dashboard.index', ['id' => $idItem, 'code' => 201])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 201]);
    }

    public function delete(Request $request)
    {
        $idLib = $request->id;
        $idItem = $request->id_item;

        // Conta quantos registros ainda existem para essa liberação, excluindo o que será deletado
        $qtdItens = ItemLiberacao::where('id', $idLib)
            ->where('id_item', '!=', $idItem)
            ->count();

        // Deleta o item cavidade
        ItemCavidadeLiberacao::where('id', $idLib)
            ->where('id_item', $idItem)
            ->delete();

        // Deleta o item
        ItemLiberacao::where('id', $idLib)
            ->where('id_item', $idItem)
            ->delete();

        // Se não existir mais nenhum item para essa liberação, remove também as cavidades
        if ($qtdItens === 0) {
            CavidadeLiberacao::where('id', $idLib)->delete();
        }

        return redirect()->route('dashboard.index', ['id' => $idLib, 'code' => 205])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 205]);
    }

    public function update(Request $request)
    {
        $idLib = $request->input('id'); // ID da liberação
        $idItem = $request->input('id_item'); // ID do item
        $idCavidade = $request->input('cavidade_id'); // ID da cavidade
        $tipo = $request->input('tipo'); // minimo ou maximo
        $valor = $request->input('valor'); // valor alterado

        // Validação simples
        if (!in_array($tipo, ['minimo', 'maximo'])) {
            return response()->json(['success' => false, 'message' => 'Campo inválido'], 400);
        }

        // Atualiza no banco
        $atualizado = ItemCavidadeLiberacao::where('id', $idLib)
            ->where('id_item', $idItem)
            ->where('id_cavidade', $idCavidade)
            ->update([
                $tipo => $valor
            ]);

        if ($atualizado) {
            return response()->json(['success' => true, 'message' => 'Valor atualizado']);
        } else {
            return response()->json(['success' => false, 'message' => 'Nenhum registro encontrado'], 404);
        }
    }



}
