<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use Illuminate\Http\Request;

class AnexosLiberacaoController extends Controller
{
    public function show($id)
    {
        $liberacao = LiberacaoProduto::findOrFail($id);

        $anexos = AnexosLiberacao::where('id', $id)
            ->orderBy('id_anx')
            ->get(); // <- Collection, não boolean, não array simples

        return view('anexos', compact('liberacao', 'anexos'));
    }

    public function destroy($id, $id_arq)
    {

    }

    public function store(Request $request, $id)
    {
        $ultimo = AnexosLiberacao::where('id', $id)
            ->orderBy('id_anx', 'desc')
            ->first();

        $proximoId = $ultimo ? $ultimo->id_anx + 1 : 1;

        $request->validate([
            'file' => 'required',
        ]);

        if ($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {

                $conteudo = file_get_contents($file->getRealPath());

                AnexosLiberacao::create([
                    'id' => $id,
                    'id_anx' => $proximoId,
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'arquivo' => $conteudo,
                ]);
            }
        }

        return redirect()->route('anexos.show', $id)->with('success', 'Anexo(s) adicionado(s) com sucesso!');
    }

    public function download($id, $id_anx)
    {
        $anexo = AnexosLiberacao::where('id', $id)
            ->where('id_anx', $id_anx)
            ->firstOrFail();

        $filename = $anexo->nome_arquivo ?: "anexo_{$id}_{$id_anx}";

        return response()->streamDownload(function () use ($anexo) {
            $blob = $anexo->arquivo;

            if (is_resource($blob)) {
                rewind($blob);
                fpassthru($blob); // escreve o stream direto na resposta
            } else {
                echo $blob ?? '';
            }
        }, $filename, [
            'Content-Type' => 'application/octet-stream',
        ]);
    }

}
