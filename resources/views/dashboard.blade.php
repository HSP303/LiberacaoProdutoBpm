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

                    <form action="{{ isset($liberacao) ? route('liberacao-produtos.update', $liberacao->id) : route('liberacao-produtos.store') }}"

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