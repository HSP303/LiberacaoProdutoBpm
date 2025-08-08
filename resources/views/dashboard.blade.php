<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liberacao de Produtos') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-4">
                    <div x-data="{ showModal: false }">
                        <form action="{{ route('dashboard.index') }}">
                            @csrf

                            <label for="id">Id. Liberação</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" name="id" id="id"
                                    class="border rounded p-2 w-full shadow-sm focus:ring focus:ring-blue-300"
                                    placeholder="Selecione um ID" required readonly>
                                <button type="button" @click="showModal = true" class="p-2 bg-gray-200 rounded">
                                    🔍
                                </button>
                            </div>

                            <!-- Modal com filtro -->
                            <div x-show="showModal"
                                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                                style="display: none;" x-data="{ filtroProduto: '', filtroEmpresa: '' }">

                                <div
                                    class="bg-white p-6 rounded-lg w-full max-w-lg max-h-[80vh] overflow-y-auto shadow-lg">
                                    <h2 class="text-lg font-bold mb-4">Selecione um ID de Liberação</h2>

                                    <!-- Campo de busca por Produto/Descrição -->
                                    <input type="text" x-model="filtroProduto"
                                        placeholder="Buscar por descrição ou produto..."
                                        class="w-full mb-4 p-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300">

                                    <!-- Campo de busca por Empresa -->
                                    <input type="text" x-model="filtroEmpresa" placeholder="Buscar por empresa..."
                                        class="w-full mb-4 p-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300">

                                    <table class="w-full table-auto text-sm border">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border px-2 py-1">ID</th>
                                                <th class="border px-2 py-1">Empresa</th>
                                                <th class="border px-2 py-1">Produto</th>
                                                <th class="border px-2 py-1">Data</th>
                                                <th class="border px-2 py-1">Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($liberacoes as $item)
                                                <tr class="hover:bg-gray-50" x-show="
                                                                                                                {{ json_encode((string) $item->produto ?? '') }}.toLowerCase().includes(filtroProduto.toLowerCase()) &&
                                                                                                                {{ json_encode((string) $item->empresa ?? '') }}.toLowerCase().includes(filtroEmpresa.toLowerCase())
                                                                                                            ">
                                                    <td class="border px-2 py-1">{{ $item->id }}</td>
                                                    <td class="border px-2 py-1">{{ $item->empresa ?? '-' }}</td>
                                                    <td class="border px-2 py-1">{{ $item->produto ?? '-' }}</td>
                                                    <td class="border px-2 py-1">
                                                        {{ $item->created_at?->format('d/m/Y') ?? '-' }}
                                                    </td>
                                                    <td class="border px-2 py-1 text-center">
                                                        <button type="button"
                                                            @click="document.getElementById('id').value = '{{ $item->id }}'; showModal = false;"
                                                            class="text-blue-600 hover:underline">
                                                            Selecionar
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    <div class="text-right mt-4">
                                        <button @click="showModal = false"
                                            class="px-4 py-1 bg-gray-300 rounded">Fechar</button>
                                    </div>
                                </div>
                            </div>



                            <div class="mt-4">
                                <x-submit-button>Filtrar Id Selecionado</x-submit-button>

                                @if(session('status_code') == 201)
                                    <x-alert title="Sucesso!">Registro inserido com sucesso!</x-alert>
                                @endif

                                @if(session('status_code') == 200)
                                    <x-alert title="Sucesso!">Registro alterado com sucesso!</x-alert>
                                @endif
                            </div>
                        </form>
                    </div>

                    <form
                        action="{{ isset($liberacao) ? route('liberacao-produtos.update', $liberacao->id) : route('liberacao-produtos.store') }}"
                        method="POST">
                        @csrf

                        @if(isset($liberacao))
                            @method('PUT')
                        @endif

                        <x-input label="Empresa" name="empresa" type="number"
                            placeholder="{{ $liberacao->empresa ?? 'Empresa' }}" required="true" />

                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <x-input label="Produto" name="produto" type="text"
                                    placeholder="{{ $liberacao->produto ?? 'Digite o produto'}}" />
                            </div>

                            <div class="w-1/2">
                                <x-input label="Fornecedor" name="fornecedor" type="text"
                                    placeholder="{{ $liberacao->fornecedor ?? 'Fornecedor ou processo interno' }}" />
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <x-input label="Qtd.Avaliada" name="qtd_avaliada" type="number"
                                    placeholder="{{ $liberacao->qtd_avaliada ?? 'Digite a Quantidade' }}" />
                            </div>

                            <div class="w-1/2">
                                <x-input label="Lote" name="lote" type="text"
                                    placeholder="{{ $liberacao->lote ?? 'Lote NF/OP/OF' }}" />
                            </div>

                            <div class="w-1/2">
                                <x-input label="Data da Revisão" name="data_revisao" type="date"
                                    placeholder="Data Revisão" />
                            </div>

                            <div class="w-1/2">
                                <x-input label="Revisão" name="revisao" type="text"
                                    placeholder="{{ $liberacao->revisao ?? 'Revisão' }}" />
                            </div>
                        </div>

                        <div x-data="{ mostrarTabela: false }" @toggle-tabela.window="mostrarTabela = !mostrarTabela">

                            <!-- Botão -->
                            <x-button-fields />
                            <br>

                            <!-- Tabela e título -->
                            <div class="flex flex-col gap-4" x-show="mostrarTabela" x-transition.duration.400ms>
                                <!-- Título Principal Centralizado -->
                                <div class="text-center">
                                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                                        Check List para testes Funcionais (Práticos)
                                    </h2>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full table-auto border border-gray-300 text-sm">
                                        <thead class="bg-gray-100 text-gray-700 font-semibold text-left">
                                            <tr>
                                                <th class="border px-4 py-2 w-1/2">Verificar</th>
                                                <th class="border px-4 py-2 w-1/4">Observação</th>
                                                <th class="border px-4 py-2 w-1/4">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $itens = [
                                                    'Interferência na Montagem',
                                                    'Folga entre Componentes',
                                                    'Aparência ( peças/regiões aparentes )',
                                                    'Outro (detalhar)',
                                                    'Impedir desmontagem Manual ( Capa Externa ) / Intencional entre os componentes',
                                                    'Introdução e Funcionamento da Chave',
                                                    'Giro Livre',
                                                    'Funcionamento das Válvulas',
                                                    'Introdução no Bocal ( Gargalo )',
                                                    'Retirada do Bocal ( Gargalo )',
                                                    'Estanqueidade',
                                                    'Cair de uma altura de 1.5m por 03 vezes e continuar atendendo os requisitos acima',
                                                    'Aparência visual',
                                                    'Teste de Campo ( Mínimo 15 dias )',
                                                    'Outro (detalhar)',
                                                    'Teste Prático',
                                                    'Verificar se os tratamentos estão de acordo com as especificações. (para produto final)',
                                                    'Teste de queda (Conforme especificado)',
                                                    'Teste de vida útil. (Conforme especificado)'
                                                ];

                                                $itensBanco = [
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
                                                ];

                                                $itensOk = [
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
                                                ]


                                            @endphp

                                            @foreach ($itens as $index => $item)
                                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                    <td class="border px-4 py-2">{{ $item }}</td>
                                                    <td class="border px-4 py-2">
                                                        <x-input label="" name="{{ $itensBanco[$index] }}" type="text"
                                                            placeholder="{{ $liberacao->{$itensBanco[$index]} ?? $item}}" />
                                                    </td>
                                                    <td class="border px-4 py-2">
                                                        <select id="{{ $itensOk[$index] }}" name="{{ $itensOk[$index] }}"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            @php
                                                                $valorAtual = $liberacao->{$itensOk[$index]} ?? '';
                                                            @endphp

                                                            <option value="" {{ $valorAtual === '' ? 'selected' : '' }}
                                                                disabled>Selecione a Opção</option>
                                                            <option value="OK" {{ $valorAtual === 'OK' ? 'selected' : '' }}>OK
                                                            </option>
                                                            <option value="NOK" {{ $valorAtual === 'NOK' ? 'selected' : '' }}>
                                                                Não OK</option>
                                                            <option value="NA" {{ $valorAtual === 'NA' ? 'selected' : '' }}>
                                                                N/A</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>




                        <br>
                        <hr>
                        <div class="mt-4">
                            <x-submit-button>Salvar Liberação</x-submit-button>

                            @if(session('status_code') == 201)
                                <x-alert title="Sucesso!">Registro inserido com sucesso!</x-alert>
                            @endif

                            @if(session('status_code') == 200)
                                <x-alert title="Sucesso!">Registro alterado com sucesso!</x-alert>
                            @endif
                        </div>
                    </form>

                    <hr>
                    <div x-data="{ showAddModal: false }">
                        <!-- Botão para abrir o modal -->
                        <button @click="showAddModal = true"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            + Adicionar Item
                        </button>

                        <!-- Modal -->
                        <div x-show="showAddModal"
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                            style="display: none;">
                            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                                <h2 class="text-lg font-bold mb-4">Adicionar Item à Liberação
                                    #{{ $liberacao->id ?? '-' }}</h2>

                                <form action="{{ route('itens-liberacao.store') }}" method="POST" class="space-y-4">
                                    @csrf

                                    <!-- id_liberacao hidden -->
                                    <input type="hidden" name="id" value="{{ $liberacao->id ?? '' }}">

                                    <div>
                                        <label class="block text-sm font-medium">ID Item</label>
                                        <input type="text" name="id_item" required
                                            class="w-full border rounded px-3 py-2">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Especificado</label>
                                        <input type="text" name="especificado" required
                                            class="w-full border rounded px-3 py-2">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Equipamento</label>
                                        <input type="text" name="equipamento" required
                                            class="w-full border rounded px-3 py-2">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium">Resultado</label>
                                        <select name="resultado" required class="w-full border rounded px-3 py-2">
                                            <option value="">Selecione</option>
                                            <option value="OK">OK</option>
                                            <option value="Não OK">Não OK</option>
                                        </select>
                                    </div>

                                    <div class="flex justify-end space-x-2 pt-4">
                                        <button type="button" @click="showAddModal = false"
                                            class="bg-gray-300 px-4 py-2 rounded">Cancelar</button>
                                        <button type="submit"
                                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-auto max-w-full border border-gray-300 rounded-md">
                        <div class="overflow-auto max-w-full border border-gray-300 rounded-md">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="sticky left-0 z-20 bg-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[100px]"
                                            style="left: 0px;">
                                            Item
                                        </th>
                                        <th
                                            class="sticky left-[55px] z-20 bg-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                            Especificado
                                        </th>
                                        <th
                                            class="sticky left-[190px] z-20 bg-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                            Equipamento
                                        </th>

                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                            Cavidade 1
                                        </th>

                                        <th
                                            class="sticky right-[80px] z-20 bg-gray-200 w-[200px] px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Seleção
                                        </th>
                                        <th
                                            class="sticky right-0 z-20 bg-gray-200 w-[200px] px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ação
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-300">
                                    @foreach ($itensLiberacao as $itens)
                                        <tr>
                                            <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap w-[100px]"
                                                style="left: 0px;">
                                                {{ $itens->id_item }}
                                            </td>
                                            <td
                                                class="sticky left-[100px] z-10 bg-white px-6 py-4 whitespace-nowrap w-[150px]">
                                                {{ $itens->especificado }}
                                            </td>
                                            <td
                                                class="sticky left-[250px] z-10 bg-white px-6 py-4 whitespace-nowrap w-[150px]">
                                                {{ $itens->equipamento }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap w-[150px]">
                                                {{ $itens->equipamento }}
                                            </td>
                                            <td
                                                class="sticky right-[100px] z-10 bg-white w-[100px] px-6 py-4 whitespace-nowrap">
                                                <form>
                                                    <select
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5">
                                                        <option selected>Selecione a Opção</option>
                                                        <option value="OK">OK</option>
                                                        <option value="NOK">Não OK</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td
                                                class="sticky right-0 z-10 bg-white w-[200px] px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex justify-end gap-2 w-full">
                                                    <form action="">
                                                        <button
                                                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                                            + Cavidade
                                                        </button>
                                                    </form>
                                                    <form action="">
                                                        <button
                                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                                            Excluir Item
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>