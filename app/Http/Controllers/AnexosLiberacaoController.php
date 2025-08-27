<?php

namespace App\Http\Controllers;

use App\Models\LiberacaoProduto;
use App\Models\AnexosLiberacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

        // Pegue o valor bruto do atributo (sem accessor)
        $raw = $anexo->getRawOriginal('arquivo');

        // Normalize minimamente
        if (is_resource($raw)) {
            rewind($raw);
            $data = stream_get_contents($raw) ?: '';
        } elseif (is_string($raw) && strncmp($raw, '\\x', 2) === 0) {
            // Formato padrão do bytea: "\xDEADBEEF..."
            $data = hex2bin(substr($raw, 2)) ?: '';
        } else {
            // Já está em bytes puros; NÃO use pg_unescape_bytea aqui
            $data = (string) $raw;
        }

        $row = DB::table('anexos_liberacao')
        ->selectRaw("nome_arquivo, encode(arquivo,'base64') AS data_b64") // evita qualquer interpretação do driver
        ->where('id', $id)
        ->where('id_anx', $id_anx)
        ->firstOrFail();

        $dataGPT = base64_decode($row->data_b64);

        $md5_data = md5($dataGPT);

        //dd($md5_data);

        $filename = $anexo->nome_arquivo ?: "anexo_{$id}_{$id_anx}";

        return response()->streamDownload(function () use ($data) {
            echo $data;
        }, $filename, [
            'Content-Type'        => 'application/pdf', // se você souber que é PDF
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

    public function probe($id, $id_anx)
    {
        $anexo = AnexosLiberacao::where('id', $id)
            ->where('id_anx', $id_anx)
            ->firstOrFail();

        // 2.1 — Leia o valor exatamente como o Eloquent entregou (com ou sem accessor)
        $valA = $anexo->arquivo; // se tiver accessor, vem “normalizado”
        $lenA = is_string($valA) ? strlen($valA) : 0;

        // 2.2 — Leia o valor BRUTO do atributo, ignorando accessor/mutator
        $valB = $anexo->getAttributes()['arquivo'] ?? null;
        $rawB = '';

        if (is_resource($valB)) {
            rewind($valB);
            $rawB = stream_get_contents($valB) ?: '';
        } elseif (is_string($valB)) {
            $rawB = $valB;
        }

        // 2.3 — Normalização “na unha” do BRUTO
        $normB = '';
        if ($rawB !== '') {
            if (strncmp($rawB, '\\x', 2) === 0) {
                $normB = hex2bin(substr($rawB, 2)) ?: '';
            } elseif (function_exists('pg_unescape_bytea')) {
                $try = @pg_unescape_bytea($rawB);
                $normB = ($try !== false) ? $try : $rawB;
            } else {
                $normB = $rawB;
            }
        }

        // 2.4 — Calcular hashes e primeiros bytes
        $md5A = $lenA ? md5($valA) : null;
        $md5B = $normB !== '' ? md5($normB) : null;

        $headA = $lenA ? bin2hex(substr($valA, 0, 8)) : null;
        $headB = $normB !== '' ? bin2hex(substr($normB, 0, 8)) : null;

        // 2.5 — Salvar duas cópias no disco para inspecionar
        if ($lenA) Storage::put("probe_A_eloquent.pdf", $valA);
        if ($normB !== '') Storage::put("probe_B_normalizado.pdf", $normB);

        // 2.6 — Consultar o tamanho direto no banco (octet_length)
        $lenDb = DB::table('anexos_liberacao')
            ->where('id', $id)
            ->where('id_anx', $id_anx)
            ->selectRaw('octet_length(arquivo) as bytes')
            ->value('bytes');

        // Logar tudo
        Log::info('PROBE anexos_liberacao', [
            'id' => $id, 'id_anx' => $id_anx,
            'lenA_eloquent' => $lenA,
            'lenB_db_octet_length' => $lenDb,
            'md5A' => $md5A,
            'md5B' => $md5B,
            'headA_hex' => $headA,     // ideal: 255044462d... (25 50 44 46 2D == %PDF-)
            'headB_hex' => $headB,
        ]);

        // Retorno simples (texto) com links p/ baixar os probes
        return response()->json([
            'lenA_eloquent' => $lenA,
            'lenB_db_octet_length' => (int) $lenDb,
            'md5A' => $md5A,
            'md5B' => $md5B,
            'headA_hex' => $headA,
            'headB_hex' => $headB,
            'probe_A_path' => Storage::path("probe_A_eloquent.pdf"),
            'probe_B_path' => Storage::path("probe_B_normalizado.pdf"),
        ]);
    }

}
