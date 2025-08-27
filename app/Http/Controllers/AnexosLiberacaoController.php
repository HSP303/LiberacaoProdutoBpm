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

        // normaliza o conteúdo do bytea para string binária correta
        $data = $this->normalizeBytea($anexo->arquivo);

        // opcional: tenta detectar o MIME (melhora a experiência no navegador)
        $mime = 'application/octet-stream';
        if (function_exists('finfo_open')) {
            $f = finfo_open(FILEINFO_MIME_TYPE);
            $detected = finfo_buffer($f, $data);
            if ($detected) $mime = $detected;
            finfo_close($f);
        }

        // retorna o arquivo para download
        return response()->streamDownload(function () use ($data) {
            echo $data; // já está como bytes puros
        }, $filename, [
            'Content-Type'        => $mime,
            'Content-Length'      => (string) strlen($data),
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

/**
 * Converte o valor vindo do PG (bytea) para bytes puros.
 * - resource: lê o stream
 * - string iniciando com "\x": decode de hex
 * - string com escapes: usa pg_unescape_bytea, se disponível
 */
    private function normalizeBytea($value): string
    {
        // Se for stream/resource, lê tudo
        if (is_resource($value)) {
            rewind($value);
            return stream_get_contents($value) ?: '';
        }

        // Se for string
        if (is_string($value)) {
            // Caso padrão do PG: "\xDEADBEEF..." (hex)
            if (strncmp($value, '\\x', 2) === 0) {
                return hex2bin(substr($value, 2)) ?: '';
            }

            // Caso "old style escapes"
            if (function_exists('pg_unescape_bytea')) {
                $un = @pg_unescape_bytea($value);
                if ($un !== false) {
                    return $un;
                }
            }

            // Já está em bytes puros
            return $value;
        }

        // Fallback
        return '';
    }

}
