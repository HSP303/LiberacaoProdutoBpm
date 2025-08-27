<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        // Se o input é file[], valide como array
        $request->validate([
            'file' => ['required', 'array'],
            'file.*' => ['file'], // pode adicionar max/mimes se quiser
        ]);

        DB::transaction(function () use ($request, $id) {

            // pega o último id_anx existente p/ este id
            $ultimo = AnexosLiberacao::where('id', $id)
                ->orderBy('id_anx', 'desc')
                ->lockForUpdate() // evita condição de corrida
                ->first();

            $proximoId = $ultimo ? $ultimo->id_anx + 1 : 1;

            foreach ($request->file('file') as $file) {
                $bytes = file_get_contents($file->getRealPath()); // bytes puros

                // Log de hash do arquivo recebido (diagnóstico)
                Log::info('UPLOAD original md5', [
                    'nome' => $file->getClientOriginalName(),
                    'md5'  => md5($bytes),
                    'id'   => $id,
                    'id_anx' => $proximoId,
                ]);

                AnexosLiberacao::create([
                    'id'           => $id,
                    'id_anx'       => $proximoId,
                    'nome_arquivo' => $file->getClientOriginalName(),
                    'arquivo'      => $bytes, // salve sem base64/escape
                ]);

                // incrementa para o próximo arquivo
                $proximoId++;
            }
        });

        return redirect()->route('anexos.show', $id)
            ->with('success', 'Anexo(s) adicionado(s) com sucesso!');
    }

    public function download($id, $id_anx)
    {
        $anexo = AnexosLiberacao::where('id', $id)
            ->where('id_anx', $id_anx)
            ->firstOrFail();

        // graças ao accessor, agora já é bytes puros
        $data = $anexo->arquivo;
        $filename = $anexo->nome_arquivo ?: "anexo_{$id}_{$id_anx}";

        // (opcional) detectar MIME
        $mime = 'application/octet-stream';
        if (function_exists('finfo_open')) {
            $fi = finfo_open(FILEINFO_MIME_TYPE);
            $detected = finfo_buffer($fi, $data);
            if ($detected) $mime = $detected;
            finfo_close($fi);
        }

        // Log de hash do que saiu do banco (diagnóstico)
        \Log::info('DOWNLOAD banco md5', [
            'nome' => $filename,
            'md5'  => md5($data),
            'id'   => $id,
            'id_anx' => $id_anx,
        ]);

        return response()->streamDownload(function () use ($data) {
            echo $data;
        }, $filename, [
            'Content-Type'            => $mime,
            'Content-Length'          => (string) strlen($data),
            'Content-Disposition'     => 'attachment; filename="'.$filename.'"',
            'X-Content-Type-Options'  => 'nosniff',
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
