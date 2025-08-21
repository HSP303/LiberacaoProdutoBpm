<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CavidadeLiberacao;
use App\Models\ItemCavidadeLiberacao;
use App\Models\ItemLiberacao;

class CavidadeController extends Controller
{
    public function store(Request $request)
    {
        $idLib = $request->id;
        $minimo = 0;
        $maximo = 0;

        // Busca o último registro para esse id
        $ultimoRegistro = CavidadeLiberacao::where('id', $idLib)
            ->orderByDesc('id_cavidade')
            ->select('descricao')
            ->first();

        if ($ultimoRegistro) {
            preg_match('/\d+$/', $ultimoRegistro->descricao, $matches);
            $numero = isset($matches[0]) ? (int) $matches[0] : 0;
            $novaDescricao = 'Cavidade ' . ($numero + 1);
            $proximo = $numero + 1;
        } else {
            $novaDescricao = 'Cavidade 1';
            $proximo = 1;
        }

        // Cria a cavidade
        CavidadeLiberacao::create([
            'id' => $idLib,
            'id_cavidade' => $proximo,
            'descricao' => $novaDescricao,
        ]);

        // Busca TODOS os itens da liberação
        $itensLiberacao = ItemLiberacao::where('id', $idLib)->get();

        // Insere a cavidade para CADA item encontrado
        foreach ($itensLiberacao as $item) {
            ItemCavidadeLiberacao::create([
                'id' => $idLib,
                'id_item' => $item->id_item, // Pega o id_item de cada registro
                'id_cavidade' => $proximo,
                'minimo' => $minimo,
                'maximo' => $maximo,
            ]);
        }

        return redirect()->route('dashboard.index', ['id' => $idLib, 'code' => 201])
            ->with(['success' => 'Cavidade criada e vinculada a todos os itens com sucesso!', 'status_code' => 201]);
    }

}
