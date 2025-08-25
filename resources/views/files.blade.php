<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Anexos') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="relative">
                            <form method="POST" action="{{ route('anexos.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <div>
                                        <x-input-label for="descricao_arquivo" :value="__('Descrição do Arquivo')" />
                                        <x-text-input id="descricao_arquivo" class="block mt-1" style="width:100%;" type="text" name="descricao_arquivo" :value="old('descricao_arquivo')" required autofocus autocomplete="representado" />
                                        <x-input-error :messages="$errors->get('descricao_arquivo')" class="mt-2" />
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 mt-4">
                                        <div class="grid grid-cols-1 gap-6 mt-2">
                                            <div>
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file">Arquivo</label>
                                                <input accept=".jpg,.jpeg,.png,.pdf" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" name="file" id="file" type="file">
                                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                   <div class="flex items-center justify-ini mt-4">
                                         <x-primary-button>
                                             {{ __('Cadastrar') }}
                                         </x-primary-button>
                                     </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="relative">
                            <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-black dark:text-gray-400">
                                    <thead class="text-xs text-black uppercase rounded-lg" style="background-color: #3b82f6; border-radius: 5px;">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Sequencia</th>
                                            <th scope="col" class="px-6 py-3">Descrição Arquivo</th>
                                            <th scope="col" class="px-6 py-3">Arquivo</th>
                                            <th scope="col" class="px-6 py-3">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($anexos as $arq)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                                <td class="px-6 py-4">{{ $arq->id_arq }}</td>
                                                <td class="px-6 py-4">{{ $arq->nome_arquivo }}</td>
                                                <td class="px-6 py-4">
                                                    <!-- Botão de três pontos -->
                                                    <div class="relative">
                                                        <button id="dropdownButton-{{ $arq->id_arq }}" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="toggleDropdown('{{ $arq->id_arq }}')">
                                                            <img src="https://cdn-icons-png.flaticon.com/512/1342/1342047.png" alt="Ações" class="w-9 h-9 ml-2">
                                                        </button>
                                                        
                                                        <!-- Dropdown menu -->
                                                        <div id="dropdownMenu-{{ $arq->id_arq }}" class="hidden absolute right-0 z-10 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 top-full">
                                                            <!-- Botão Visualizar Documento -->
                                                            <div class="flex items-center">
                                                                <img src="https://cdn-icons-png.flaticon.com/512/475/475990.png" alt="Visualizar" class="w-8 h-8 ml-2">
                                                                <button type="button" class="text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                                    onclick="viewDocument('{{ url( $arq->arquivo) }}', '{{ pathinfo($arq->arquivo, PATHINFO_EXTENSION) }}'); toggleDropdown('{{ $arq->id_arq }}')">
                                                                    Visualizar
                                                                </button>
                                                            </div>
                                                        
                                                            <div class="flex items-center">
                                                                <img src="https://cdn-icons-png.flaticon.com/512/724/724933.png" alt="Baixar" class="w-8 h-8 ml-2">
                                                                <a href="{{ $arq->downloadUrl() }}" class="text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" download>
                                                                    Baixar
                                                                </a>
                                                            </div>
                                                        
                                                            <form action="{{ route('anexos.destroy', [$arq->id, 'id_doc' => $arq->id_arq]) }}" method="POST" class="block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <div class="flex items-center">
                                                                    <img src="https://cdn-icons-png.flaticon.com/512/1799/1799391.png" alt="Excluir" class="w-8 h-8 ml-2">
                                                                    <button type="submit" class="text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" onclick="confirmDeletion({{ $arq->id_arq }}); toggleDropdown('{{ $arq->id_arq }}')">Excluir</button>
                                                                </div>
                                                            </form>
                                                        </div>
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
    </div>

    <!-- Modal para pré-visualização do documento -->
    <div id="documentModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <!-- Alterado para ocupar 100% da largura e altura -->
        <div class="bg-white w-full h-full p-4 relative rounded-lg">
            <button onclick="closeDocumentPreview()" class="absolute top-2 right-2 text-black text-xl font-bold">✕</button>

            <!-- Container para exibir o documento -->
            <div id="documentContainer" class="w-full h-full overflow-auto">
                <!-- O conteúdo (iframe ou img) será inserido aqui dinamicamente -->
            </div>
        </div>
    </div>
    
</x-app-layout>