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
                                    placeholder="Selecione um ID" required readonly
                                    value="{{ old('id', $liberacoes->first()?->id ?? '') }}" />
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
                                            @foreach ($liberacoes as $item)
                                                <tr class="hover:bg-gray-50"
                                                    x-show="
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

                                @if (session('status_code') == 201)
                                    <x-alert title="Sucesso!">Registro inserido com sucesso!</x-alert>
                                @endif

                                @if (session('status_code') == 200)
                                    <x-alert title="Sucesso!">Registro alterado com sucesso!</x-alert>
                                @endif

                                @if (session('status_code') == 205)
                                    <x-alert title="Sucesso!">Registro excluido com sucesso!</x-alert>
                                @endif

                                @if (session('status_code') == 206)
                                    <x-alert title="Sucesso!">Registro alterado com sucesso!</x-alert>
                                @endif
                            </div>
                        </form>
                    </div>

                    <form
                        action="{{ isset($liberacao) ? route('liberacao-produtos.update', $liberacao->id) : route('liberacao-produtos.store') }}"
                        method="POST">
                        @csrf

                        @if (isset($liberacao))
                            @method('PUT')
                        @endif

                        <div class="mb-6">
                            <label for="empresa" class="block text-lg font-medium text-gray-700 mb-2">Empresa</label>
                            <select id="empresa" name="empresa"
                                class="border rounded p-2 w-full shadow-sm focus:ring focus:ring-blue-300 text-black">
                                <option value="">Selecione a empresa</option>
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa['codemp'] ?? '' }}"
                                        @if (($liberacao->empresa ?? '') == ($empresa['codemp'] ?? '')) selected @endif>
                                        {{ $empresa['codemp'] . ' - ' . ($empresa['nomemp'] ?? 'Sem nome') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <label for="produto"
                                    class="block text-lg font-medium text-gray-700 mb-3 mt-3">Produto</label>

                                <div class="flex">
                                    <input type="text" id="busca-produto" placeholder="Digite para buscar"
                                        class="border rounded-l p-2 w-full shadow-sm focus:ring focus:ring-blue-300 text-black">
                                    <button type="button" id="btn-buscar-produto"
                                        class="bg-blue-500 text-white px-3 rounded-r hover:bg-blue-600">
                                        🔍
                                    </button>
                                </div>

                                <select id="lista-produtos" name="produto" required
                                    class="border rounded p-2 w-full shadow-sm focus:ring focus:ring-blue-300 text-black mt-2">
                                    <option value="">Selecione o produto</option>
                                    @if ($liberacao && $liberacao->produto)
                                        <option value="{{ $liberacao->produto }}" selected>
                                            {{ $liberacao->produto }}
                                        </option>
                                    @endif
                                </select>
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
                                                    'Teste de vida útil. (Conforme especificado)',
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
                                                ];

                                            @endphp

                                            @foreach ($itens as $index => $item)
                                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                    <td class="border px-4 py-2">{{ $item }}</td>
                                                    <td class="border px-4 py-2">
                                                        <x-input label="" name="{{ $itensBanco[$index] }}"
                                                            type="text"
                                                            placeholder="{{ $liberacao->{$itensBanco[$index]} ?? $item }}" />
                                                    </td>
                                                    <td class="border px-4 py-2">
                                                        <select id="{{ $itensOk[$index] }}"
                                                            name="{{ $itensOk[$index] }}"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                            @php
                                                                $valorAtual = $liberacao->{$itensOk[$index]} ?? '';
                                                            @endphp

                                                            <option value=""
                                                                {{ $valorAtual === '' ? 'selected' : '' }} disabled>
                                                                Selecione a Opção</option>
                                                            <option value="OK"
                                                                {{ $valorAtual === 'OK' ? 'selected' : '' }}>OK
                                                            </option>
                                                            <option value="NOK"
                                                                {{ $valorAtual === 'NOK' ? 'selected' : '' }}>
                                                                Não OK</option>
                                                            <option value="NA"
                                                                {{ $valorAtual === 'NA' ? 'selected' : '' }}>
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

                            @if (session('status_code') == 201)
                                <x-alert title="Sucesso!">Registro inserido com sucesso!</x-alert>
                            @endif

                            @if (session('status_code') == 200)
                                <x-alert title="Sucesso!">Registro alterado com sucesso!</x-alert>
                            @endif
                        </div>
                    </form>

                    <hr>
                    <div x-data="{ showAddModal: false }">

                        <div class="flex gap-4 items-center">
                            <!-- Botão Adicionar Item -->
                            <button @click="showAddModal = true"
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                + Adicionar Item
                            </button>

                            <!-- Botão Adicionar Cavidade -->
                            <form action="{{ route('cavidades-liberacao.store') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="id" value="{{ $liberacao->id ?? '' }}">
                                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                                    title="Adicionar Cavidade">
                                    + Cavidade
                                </button>
                            </form>
                        </div>

                        <!-- Modal -->
                        <div x-show="showAddModal"
                            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                            style="display: none;">
                            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg">
                                <h2 class="text-lg font-bold mb-4">Adicionar Item à Liberação
                                    #{{ $liberacao->id ?? '-' }}</h2>

                                <form action="{{ route('itens-liberacao.store') }}" method="POST"
                                    class="space-y-4">
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
                                            class="sticky left-[180px] z-20 bg-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                            Equipamento
                                        </th>

                                        @foreach ($cavidadesLiberacao as $cavidade)
                                            <th
                                                class="z-20 bg-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                                {{ $cavidade->descricao }}
                                            </th>
                                        @endforeach

                                        <th
                                            class="sticky right-0 z-20 bg-gray-200 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">
                                            Ações da Tabela
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
                                                class="sticky left-[55px] z-10 bg-white px-6 py-4 whitespace-nowrap w-[150px]">
                                                {{ $itens->especificado }}
                                            </td>
                                            <td
                                                class="sticky left-[180px] z-10 bg-white px-6 py-4 whitespace-nowrap w-[150px]">
                                                {{ $itens->equipamento }}
                                            </td>

                                            <form onsubmit="return false;">
                                                @csrf
                                                @method('PUT')
                                                @foreach ($itensCavidadesLiberacao as $itemCavidade)
                                                    <td class="px-6 py-3 whitespace-nowrap w-[150px]">
                                                        <div class="flex gap-2 items-center">
                                                            <!-- Hidden inputs -->
                                                            <input type="hidden" name="id"
                                                                value="{{ $itemCavidade->id }}">
                                                            <input type="hidden" name="id_item"
                                                                value="{{ $itemCavidade->id_item }}">

                                                            <!-- Min input -->
                                                            <div class="flex flex-col items-start">
                                                                <label for="minimo_{{ $itemCavidade->id_cavidade }}"
                                                                    class="text-[10px] font-medium text-gray-600">Min</label>
                                                                <input type="number"
                                                                    class="input-cavidade w-14 text-xs border border-gray-300 rounded px-1 py-0.5"
                                                                    data-id="{{ $itemCavidade->id_cavidade }}"
                                                                    data-type="minimo"
                                                                    value="{{ $itemCavidade->minimo }}" />
                                                            </div>

                                                            <!-- Max input -->
                                                            <div class="flex flex-col items-start">
                                                                <label for="maximo_{{ $itemCavidade->id_cavidade }}"
                                                                    class="text-[10px] font-medium text-gray-600">Max</label>
                                                                <input type="number"
                                                                    class="input-cavidade w-14 text-xs border border-gray-300 rounded px-1 py-0.5"
                                                                    data-id="{{ $itemCavidade->id_cavidade }}"
                                                                    data-type="maximo"
                                                                    value="{{ $itemCavidade->maximo }}" />
                                                            </div>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </form>

                                            <td
                                                class="sticky right-0 z-10 bg-white w-[400px] px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center justify-end gap-2 w-full">

                                                    <form>
                                                        <select
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg">
                                                            <option value=""
                                                                {{ $itens->resultado === '' ? 'selected' : '' }}
                                                                disabled>Selecione a Opção</option>
                                                            <option value="OK"
                                                                {{ $itens->resultado === 'OK' ? 'selected' : '' }}>OK
                                                            </option>
                                                            <option value="Não OK"
                                                                {{ $itens->resultado === 'Não OK' ? 'selected' : '' }}>
                                                                Não OK</option>
                                                        </select>
                                                    </form>

                                                    <!-- Botão Excluir -->
                                                    <form action="{{ route('itens-liberacao.delete') }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <input type="hidden" name="id"
                                                            value="{{ $itens->id }}">
                                                        <input type="hidden" name="id_item"
                                                            value="{{ $itens->id_item }}">
                                                        <button
                                                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700"
                                                            title="Excluir Item">
                                                            -
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
                <br>
                <hr>
                <br>
                <a href="{{ $liberacao?->id ? route('liberacao.pdf', $liberacao->id) : '#' }}"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-xs font-medium rounded-md shadow hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    📄 Gerar PDF
                </a>
            </div>
        </div>
    </div>



    <script>
        document.querySelectorAll('.input-cavidade').forEach(input => {
            input.addEventListener('change', function() {
                const td = this.closest('td'); // pega o pai da linha
                const id = td.querySelector('input[name="id"]').value;
                const id_item = td.querySelector('input[name="id_item"]').value;

                const cavidade_id = this.dataset.id; // ID da cavidade
                const tipo = this.dataset.type; // minimo ou maximo
                const valor = this.value;

                fetch("{{ route('itens-liberacao.update') }}", {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id: id,
                            id_item: id_item,
                            cavidade_id: cavidade_id,
                            tipo: tipo,
                            valor: valor
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Atualizado:', data);
                    })
                    .catch(error => console.error('Erro:', error));
            });
        });

        // Zera o campo de produtos ao trocar de empresa
        document.getElementById('empresa').addEventListener('change', function() {
            const select = document.getElementById('lista-produtos');
            const inputBusca = document.getElementById('busca-produto');
            select.innerHTML = '<option value="">Selecione o produto</option>';
            inputBusca.value = ''; // opcional: limpa também a busca
        });

        document.getElementById('btn-buscar-produto').addEventListener('click', function() {
            const termo = document.getElementById('busca-produto').value;
            const empresa = document.getElementById('empresa').value;

            if (!empresa) {
                alert('Selecione a empresa antes de buscar o produto.');
                return;
            }

            fetch(`{{ route('buscar.produtos') }}?empresa=${empresa}&termo=${encodeURIComponent(termo)}`)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('lista-produtos');
                    const produtoAtual = "{{ $liberacao->produto ?? '' }}"; // Produto atual da liberação

                    select.innerHTML = '<option value="">Selecione o produto</option>'; // limpa opções

                    data.forEach(produto => {
                        const option = document.createElement('option');
                        option.value = produto.codpro; // valor enviado no form
                        option.textContent =
                            `${produto.codpro} - ${produto.despro}`; // mostra código e descrição

                        if (produto.codpro === produtoAtual) {
                            option.selected = true;
                        }

                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao buscar produtos:', error);
                });
        });
    </script>
</x-app-layout>
