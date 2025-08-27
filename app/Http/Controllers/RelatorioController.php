<?php

// app/Http/Controllers/RelatorioController.php
namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\LiberacaoProduto;      // ajuste se o nome for outro
use App\Models\AnexosLiberacao;       // se quiser mostrar anexos/resumo

class RelatorioController extends Controller
{
    public function liberacao(int $id)
    {
        // Carregue tudo que precisa no relatório
        $lib = LiberacaoProduto::query()
            ->with([
                // relações úteis no PDF (ajuste p/ seu domínio):
                // 'cliente', 'itens', 'usuario', ...
            ])
            ->findOrFail($id);

        // Ex.: contar anexos ou pegar últimos
        $anexos = AnexosLiberacao::where('id', $id)
            ->orderBy('id_anx')
            ->get(['id', 'id_anx', 'nome_arquivo']); // não traga o bytea pro PDF

        // Gera o PDF a partir da view Blade
        $pdf = Pdf::loadView('relatorios.liberacao', [
            'lib'    => $lib,
            'anexos' => $anexos,
        ])->setPaper('a4', 'portrait'); // ou 'landscape'

        // Exibir no navegador (inline). Use ->download(...) para baixar.
        return $pdf->stream("liberacao-{$id}.pdf");
        // return $pdf->download("liberacao-{$id}.pdf");
    }
}
