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
                    <div class="flex gap-4 mr-10 ml-10">
                        <div class="w-1/2">
                            <x-input label="Empresa" name="empresa" type="number" placeholder="Digite a empresa"
                                required="true" />
                        </div>
                        <div class="w-1/2">
                            <x-input label="Produto" name="produto" type="text" placeholder="Digite o Produto" />
                        </div>
                    </div>
                    <hr>
                    <div class="overflow-auto max-w-full border border-gray-300 rounded-md">
                    <x-table_liberacao/>    
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>