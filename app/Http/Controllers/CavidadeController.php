<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CavidadeLiberacao;

class CavidadeController extends Controller
{
    public function store(Request $request)
    {
        $idLib = $request->id;
        $idItem = $request->id_item;
        $minimo = 0;
        $maximo = 0;

        // Busca o último registro para esse id e id_item
        $ultimoRegistro = CavidadeLiberacao::where('id', $idLib)
            ->where('id_item', $idItem)
            ->orderByDesc('id') // ou pelo campo de data se preferir
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

        CavidadeLiberacao::create([
            'id' => $idLib,
            'id_item' => $idItem,
            'id_cavidade' => $proximo,
            'descricao' => $novaDescricao,
            'minimo' => $minimo,
            'maximo' => $maximo,
        ]);

        return redirect()->route('dashboard.index', ['id' => $idLib, 'id_item' => $idItem, 'code' => 201])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 201]);
    }

}
