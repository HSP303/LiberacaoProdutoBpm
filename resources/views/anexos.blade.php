<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Anexos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex flex-col gap-4">
                    <form method="POST" action="{{ route('anexos.store', $liberacao->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div>
                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900"
                                        for="file">Arquivo</label>
                                    <input accept=".jpg,.jpeg,.png,.pdf"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 text-gray-400 focus:outline-none"
                                        name="file[]" id="file" type="file" multiple>
                                    <x-input-error :messages="$errors->get('file')" class="mt-2" />
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-900 rounded-2xl shadow-md ring-1 ring-gray-200 dark:ring-gray-800 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Anexos da Liberação
                            #{{ $liberacao->id }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Lista de arquivos vinculados</p>
                    </div>
                    <span
                        class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 text-xs font-medium">
                        {{ $anexos->count() }} itens
                    </span>
                </div>

                <!-- Table -->
                <div class="relative overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-blue-600">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                    Seq. Anexo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                    Descrição Arquivo</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-white">
                                    Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse($anexos as $anx)
                                <tr
                                    class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors">
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">{{ $anx->id_anx }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $anx->nome_arquivo }}</td>
                                    <td class="px-6 py-4 text-center space-x-2">
                                        <!-- Botão Baixar -->
                                        <a href="{{ route('anexos.download', ['id' => $anx->id, 'id_anx' => $anx->id_anx]) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-md shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            ⬇️ Baixar
                                        </a>

                                        <!-- Botão Excluir -->
                                        <form action="{{ route('anexos.destroy', $anx->id, $anx->id_anx) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                                                onclick="return confirm('Tem certeza que deseja excluir este anexo?')">
                                                🗑️ Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        Nenhum anexo encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
