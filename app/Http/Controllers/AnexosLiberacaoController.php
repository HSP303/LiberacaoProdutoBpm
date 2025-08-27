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

    public function destroy($id, $id_anx)
    {
        // sua lógica de exclusão
        $anexo = AnexosLiberacao::where('id', $id)
                      ->where('id_anx', $id_anx)
                        ->firstOrFail();

        $anexo->delete();

        return redirect()->back()->with('success', 'Anexo excluído com sucesso!');
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
                    'arquivo'      => base64_encode($bytes), // salve sem base64/escape
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

        $arquivo = $raw;
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

        return response()->streamDownload(function () use ($arquivo) {
            echo $arquivo;
        }, $filename, [
            'Content-Type'        => 'application/pdf', // se você souber que é PDF
            'Content-Length'      => (string) strlen($arquivo),
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

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

    public function GPTLixoStore(Request $request, $id)
    {
        $ultimo = AnexosLiberacao::where('id', $id)
            ->orderBy('id_anx', 'desc')
            ->lockForUpdate() // evita condição de corrida
            ->first();

        $proximoId = $ultimo ? $ultimo->id_anx + 1 : 1;

        foreach ($request->file('file') as $uploadedFile) {
            $name = $uploadedFile->getClientOriginalName();
            $data = base64_encode(file_get_contents($uploadedFile->getRealPath()));

            DB::table('anexos_liberacao')->insert([
                'id'          => $id,
                'id_anx'      => $proximoId++,
                'nome_arquivo'=> $name,
                'arquivo'     => $data
            ]);
        }

        return redirect()->route('GPTShow', $id)
            ->with('success', 'Anexo(s) adicionado(s) com sucesso!');
    }

    public function GPTLixoShow(int $id)
    {
        $pdf = DB::table('anexos_liberacao')
          ->where('id', $id)
          ->orderBy('id_anx', 'desc')
          ->first();

        if (!$pdf) {
            abort(404);
        }

        $content = base64_decode($pdf->arquivo);

        $response = Response::make($content, 200);
        $response->header('Content-Type', 'application/pdf');

        return $response;
    }
}

