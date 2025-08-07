<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiberacaoProduto;
use Illuminate\Support\Facades\Auth;


class LiberacaoProdutos extends Controller
{
    public function index(Request $request)
    {
        $idLiberacao = $request->query('id');
        $liberacao = null;

        if ($idLiberacao) {
            $liberacao = LiberacaoProduto::find($idLiberacao);

            return view('dashboard', compact('liberacao'));
        } else {

            return view('dashboard');
        }
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'empresa' => 'required|integer',
            'produto' => 'required|string',
            'fornecedor' => 'nullable|string',
            'qtd_avaliada' => 'required|integer',
            'lote' => 'nullable|string',
            'data_revisao' => 'nullable|date',
            'revisao' => 'nullable|string',
        ]);

        // Armazena a instância criada
        $liberacao = LiberacaoProduto::create($validated);

        // Acessa o ID gerado
        $idCriado = $liberacao->id;

        return redirect()->route('dashboard.index', ['id' => $idCriado, 'code' => 201])
                         ->with(['success'=>'Produto liberado com sucesso!', 'status_code'=> 201]);
    }

    public function update(Request $request, $id)
    {
        $liberacao = LiberacaoProduto::findOrFail($id);

        $data = $request->only([
            'empresa',
            'produto',
            'fornecedor',
            'qtd_avaliada',
            'lote',
            'data_revisao',
            'revisao',
        ]);

        // Preencher com valores antigos se algum campo estiver faltando
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $data[$key] = $liberacao->$key;
            }
        }

        $liberacao->update($data);
        
        return redirect()->route('dashboard.index', ['id' => $id, 'code' => 200])
                         ->with(['success'=>'Produto liberado com sucesso!', 'status_code'=> 200]);
    }


}
