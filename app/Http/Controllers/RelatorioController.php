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
        $itens = ItemLiberacao::findOrFail($id);
        $idItem = $itens->id_item;

        // Busca Cavidades
        $cavidades = CavidadeLiberacao::findOrFail('id', $id);
        $idItem = $cavidades->id_cavidade;

        $itensCavidades = ItemCavidadeLiberacao::where('id', $id)
            ->where('id_item', $idItem)
            ->findOrFail();

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
