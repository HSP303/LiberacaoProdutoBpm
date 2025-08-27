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
        $lib = LiberacaoProduto::with([
            // carrega relações se precisar, ex:
            // 'cliente', 'usuario', 'itens'
        ])->findOrFail($id);

        // Buscar itens (se houver)
        $itens = $lib->itens ?? []; // ajuste conforme sua model

        // Buscar cavidades (ajuste conforme tabela real)
        // Exemplo: $cavidades = Cavidades::where('liberacao_id', $id)->get();
        $cavidades = [];

        // Buscar anexos (traga mime + arquivo em bytea)
        $anexos = AnexosLiberacao::where('id', $id)
            ->orderBy('id_anx')
            ->get();

        // Checklist (se existir tabela, substitua aqui)
        $check = [
            'montagem_ok'   => '',
            'montagem_nao'  => '',
            'obs_montagem'  => '',
            'pratico_ok'    => '',
            'pratico_nao'   => '',
            'obs_pratico'   => '',
            'aparencia_ok'  => '',
            'aparencia_nao' => '',
            'obs_aparencia' => '',
            'vida_ok'       => '',
            'vida_nao'      => '',
            'obs_vida'      => '',
        ];

        // Gerar PDF usando a view
        $pdf = Pdf::loadView('relatorios.liberacao', [
            'lib'       => $lib,
            'itens'     => $itens,
            'cavidades' => $cavidades,
            'check'     => $check,
            'anexos'    => $anexos,
        ])->setPaper('a4', 'portrait');

        // Exibir inline
        return $pdf->stream("liberacao-{$id}.pdf");

        // Para forçar download:
        // return $pdf->download("liberacao-{$id}.pdf");
    }
}
