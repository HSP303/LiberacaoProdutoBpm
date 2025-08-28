<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use App\Models\CavidadeLiberacao;
use App\Models\ItemCavidadeLiberacao;
use App\Models\ItemLiberacao;

class RelatorioController extends Controller
{
    public function liberacao(int $id)
    {
        // Buscar dados principais da liberação
        $lib = LiberacaoProduto::findOrFail($id);

        // Buscar itens (se houver)
        $itens = ItemLiberacao::where('id', $id)->get();
        $idItem = $itens->id_item;

        // Busca Cavidades
        $cavidades = CavidadeLiberacao::where('id', $id)->get();
        $idItem = $cavidades->id_cavidade;

        $itensCavidades = ItemCavidadeLiberacao::where('id', $id)
        ->when($itens->isNotEmpty(), fn($q) =>
            $q->whereIn('id_item', $itens->pluck('id_item'))
        )
        ->get();

        // Buscar anexos (traga mime + arquivo em bytea)
        $anexos = AnexosLiberacao::where('id', $id)
        ->orderBy('id_anx')
        ->get();

        // Gerar PDF usando a view
        $pdf = Pdf::loadView('relatorios.liberacao', [
            'lib'       => $lib,
            'itens'     => $itens,
            'cavidades' => $cavidades,
            'itemCavidade' => $itensCavidades,
            'anexos'    => $anexos,
        ])->setPaper('a4', 'portrait');

        return $pdf->download("liberacao-{$id}.pdf");
    }
}
