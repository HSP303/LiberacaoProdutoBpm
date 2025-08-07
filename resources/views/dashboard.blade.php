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
                    <div class="overflow-auto max-w-full border border-gray-300 rounded-md">
                        <x-table_liberacao />
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>