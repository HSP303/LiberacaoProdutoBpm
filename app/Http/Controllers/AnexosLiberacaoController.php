<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use Illuminate\Http\Request;

class AnexosLiberacaoController extends Controller
{
    public function show($id)
    {
        // Verifica se ID existe na tabela de liberações
        $anexos = AnexosLiberacao::find($id);

        return view('anexos', compact('anexos', 'id'));
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

        // retorna o arquivo binário com headers corretos
        return response($anexo->arquivo)
            ->header('Content-Type', 'application/octet-stream')
            ->header('Content-Disposition', 'attachment; filename="' . $anexo->nome_arquivo . '"');
    }

}
