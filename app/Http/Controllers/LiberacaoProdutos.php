<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiberacaoProduto;
use App\Models\ItemLiberacao;
use App\Models\CavidadeLiberacao;
use App\Models\ItemCavidadeLiberacao;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Pail\ValueObjects\Origin\Console;
use function Laravel\Prompts\select;


class LiberacaoProdutos extends Controller
{
    public function index(Request $request)
    {
        $idLiberacao = $request->query('id');
        $liberacao = null;
        $itensLiberacao = collect();
        $cavidadesLiberacao = collect();
        $itensCavidadeLiberacao = collect();
        $empSelecionada = '';

        $user = Auth::user();

        $token = $user->token;

        $bearer = 'Bearer ' . $token;

        // BUSCA DADOS DAS EMPRESAS 
        $this->client = new Client();

        $response = $this->client->request('POST', 'https://platform.senior.com.br/t/senior.com.br/bridge/1.0/rest/platform/conector/actions/invoke', [
            'json' => [
                'inputData' => [
                    'server' => 'https://senior.gramserv.com.br:8081',
                    'rootObject' => '',
                    'service' => 'com.avs.SeniorX',
                    'module' => 'sapiens',
                    'encryption' => '0',
                    'port' => 'BuscaEmpresa'
                ],
                'id' => 'f2200c3b-c7df-4040-9613-34f697b75889',
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $bearer,
            ]
        ]);

        $responseDataEmpresa = json_decode($response->getBody()->getContents(), true);
        $empresas = $responseDataEmpresa['outputData']['empresas'];


        $liberacoes = LiberacaoProduto::select('id', 'empresa', 'produto', 'created_at')->get();

        if ($idLiberacao) {
            $liberacao = LiberacaoProduto::find($idLiberacao);

            $itensLiberacao = ItemLiberacao::where('id', $idLiberacao)
                ->select('id', 'id_item', 'especificado', 'equipamento', 'resultado')
                ->get();

            $idItem = $itensLiberacao->first()->id_item ?? null;


            $cavidadesLiberacao = CavidadeLiberacao::where('id', $idLiberacao)
                ->select('id', 'id_cavidade', 'descricao')
                ->orderBy('id_cavidade', 'asc')
                ->get();

            $itensCavidadeLiberacao = ItemCavidadeLiberacao::where('id', $idLiberacao)
                ->where('id_item', $idItem)
                ->select('id', 'id_item', 'id_cavidade', 'minimo', 'maximo')
                ->orderBy('id_cavidade', 'asc')
                ->get();
        }

        return view('dashboard', [
            'liberacao' => $liberacao,
            'liberacoes' => $liberacoes,
            'itensLiberacao' => $itensLiberacao,
            'cavidadesLiberacao' => $cavidadesLiberacao,
            'itensCavidadesLiberacao' => $itensCavidadeLiberacao,
            'empresas' => $empresas,
        ]);
    }

    public function getIds(Request $request)
    {
        $idsLiberacoes = LiberacaoProduto::pluck('id');
        return view('dashboard.index', compact('idsLiberacoes'));
    }

    public function buscarProdutos(Request $request)
    {
        $empresaId = $request->param('empresaId');
  
        $user = Auth::user();
        $token = $user->token;
        $bearer = 'Bearer ' . $token;

        $this->client = new Client();
        $response = $this->client->request('POST', 'https://platform.senior.com.br/t/senior.com.br/bridge/1.0/rest/platform/conector/actions/invoke', [
            'json' => [
                'inputData' => [
                    'server' => 'https://senior.gramserv.com.br:8081',
                    'rootObject' => '',
                    'service' => 'com.avs.SeniorX',
                    'module' => 'sapiens',
                    'encryption' => '0',
                    'port' => 'BuscaProduto',
                    'despro' => '',
                    'codemp' => $empresaId, // ← aqui vai a empresa selecionada
                    'codpro' => '',
                    'top' => '500000',
                    'skip' => '0',
                ],
                'id' => 'f2200c3b-c7df-4040-9613-34f697b75889',
                'configurationId' => '7afdb58f-a138-4005-b3f2-f9d9f124459a',
            ],
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $bearer,
            ]
        ]);

        $responseDataProduto = json_decode($response->getBody()->getContents(), true);
        $produtos = $responseDataProduto['outputData']['produtos'] ?? [];

        return response()->json($produtos);
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
            'interferencia_montagem' => 'nullable|string',
            'folga_componentes' => 'nullable|string',
            'aparencia' => 'nullable|string',
            'outro_cinco' => 'nullable|string',
            'impedir_desmontagem' => 'nullable|string',
            'introducao_funcionamento' => 'nullable|string',
            'giro_livre' => 'nullable|string',
            'funcionamento_valvula' => 'nullable|string',
            'introducao_bocal' => 'nullable|string',
            'retirada_bocal' => 'nullable|string',
            'estanqueidade' => 'nullable|string',
            'altura_requisitos' => 'nullable|string',
            'aparencia_visual' => 'nullable|string',
            'teste_campo' => 'nullable|string',
            'outro_tres' => 'nullable|string',
            'teste_pratico' => 'nullable|string',
            'tratamentos_especificacoes' => 'nullable|string',
            'teste_queda' => 'nullable|string',
            'teste_vida' => 'nullable|string',
            'ok_interferencia_montagem' => 'nullable|string',
            'ok_folga_componentes' => 'nullable|string',
            'ok_aparencia' => 'nullable|string',
            'ok_outro_cinco' => 'nullable|string',
            'ok_impedir_desmontagem' => 'nullable|string',
            'ok_introducao_funcionamento' => 'nullable|string',
            'ok_giro_livre' => 'nullable|string',
            'ok_funcionamento_valvula' => 'nullable|string',
            'ok_introducao_bocal' => 'nullable|string',
            'ok_retirada_bocal' => 'nullable|string',
            'ok_estanqueidade' => 'nullable|string',
            'ok_altura_requisitos' => 'nullable|string',
            'ok_aparencia_visual' => 'nullable|string',
            'ok_teste_campo' => 'nullable|string',
            'ok_outro_tres' => 'nullable|string',
            'ok_teste_pratico' => 'nullable|string',
            'ok_tratamentos_especificacoes' => 'nullable|string',
            'ok_teste_queda' => 'nullable|string',
            'ok_teste_vida' => 'nullable|string',
        ]);

        // Armazena a instância criada
        $liberacao = LiberacaoProduto::create($validated);

        // Acessa o ID gerado
        $idCriado = $liberacao->id;

        return redirect()->route('dashboard.index', ['id' => $idCriado, 'code' => 201])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 201]);
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
            'interferencia_montagem',
            'folga_componentes',
            'aparencia',
            'outro_cinco',
            'impedir_desmontagem',
            'introducao_funcionamento',
            'giro_livre',
            'funcionamento_valvula',
            'introducao_bocal',
            'retirada_bocal',
            'estanqueidade',
            'altura_requisitos',
            'aparencia_visual',
            'teste_campo',
            'outro_tres',
            'teste_pratico',
            'tratamentos_especificacoes',
            'teste_queda',
            'teste_vida',
            'ok_interferencia_montagem',
            'ok_folga_componentes',
            'ok_aparencia',
            'ok_outro_cinco',
            'ok_impedir_desmontagem',
            'ok_introducao_funcionamento',
            'ok_giro_livre',
            'ok_funcionamento_valvula',
            'ok_introducao_bocal',
            'ok_retirada_bocal',
            'ok_estanqueidade',
            'ok_altura_requisitos',
            'ok_aparencia_visual',
            'ok_teste_campo',
            'ok_outro_tres',
            'ok_teste_pratico',
            'ok_tratamentos_especificacoes',
            'ok_teste_queda',
            'ok_teste_vida',
        ]);

        // Preencher com valores antigos se algum campo estiver faltando
        foreach ($data as $key => $value) {
            if (is_null($value)) {
                $data[$key] = $liberacao->$key;
            }
        }

        $liberacao->update($data);

        return redirect()->route('dashboard.index', ['id' => $id, 'code' => 200])
            ->with(['success' => 'Produto liberado com sucesso!', 'status_code' => 200]);
    }


}
