<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

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

    public function destroy($id, $id_anx)
    {
        // sua lógica de exclusão
        $anexo = AnexosLiberacao::where('id', $id)
                      ->where('id_anx', $id_anx)
                      ->delete();

        return redirect()->back()->with('success', 'Anexo excluído com sucesso!');
    }

    public function store(Request $request, $id)
    {
        $ultimo = AnexosLiberacao::where('id', $id)
            ->orderBy('id_anx', 'desc')
            ->lockForUpdate() // evita condição de corrida
            ->first();

        $proximoId = $ultimo ? $ultimo->id_anx + 1 : 1;

        foreach ($request->file('file') as $uploadedFile) {
            $name = $uploadedFile->getClientOriginalName();
            $data = base64_encode(file_get_contents($uploadedFile->getRealPath()));

            AnexosLiberacao::create([
                'id'          => $id,
                'id_anx'      => $proximoId++,
                'nome_arquivo'=> $name,
                'arquivo'     => $data
            ]);
        }

        return redirect()->route('anexos.show', $id)
            ->with('success', 'Anexo(s) adicionado(s) com sucesso!');
    }

    public function download(int $id, $id_anx)
    {
        $pdf = AnexosLiberacao::where('id', $id)
          ->where('id_anx', $id_anx)
          ->first();

        if (!$pdf) {
            abort(404);
        }

        $arquivo = $pdf->arquivo;

        if (is_resource($arquivo)) {
            // Caso venha como resource (stream)
            rewind($arquivo);
            $arquivoString = stream_get_contents($arquivo) ?: '';
        }
        elseif (is_string($arquivo) && strncmp($arquivo, '\\x', 2) === 0) {
            // Caso venha como "\xDEADBEEF..."
            $arquivoString = hex2bin(substr($arquivo, 2)) ?: '';
        }
        elseif (is_string($arquivo)) {
            // Já é string (pode ser bytes ou até base64)
            $arquivoString = $arquivo;
        }
        else {
            // Último caso: força conversão
            $arquivoString = (string) $arquivo;
        }

        $content = base64_decode($arquivoString);

        $filename = $pdf->nome_arquivo ?: "anexo_{$id}_{$id_anx}";
        $response = Response::make($content, 200);
        $response->header('Content-Type', 'application/pdf');

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type'        => 'application/pdf', // se você souber que é PDF
            'Content-Length'      => (string) strlen($content),
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}

