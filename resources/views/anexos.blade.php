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
                            <form method="POST" action="{{ route('anexos.store', ['id' => $id]) }}"
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
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div
            class="bg-white dark:bg-gray-900 rounded-2xl shadow-md ring-1 ring-gray-200 dark:ring-gray-800 overflow-hidden">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Anexos da Liberação</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Lista de arquivos vinculados</p>
                </div>
                <span
                    class="inline-flex items-center rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 text-xs font-medium">
                    2 itens
                </span>
            </div>

            <!-- Table -->
            <div class="relative overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-blue-600">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Seq. Anexo</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-white">
                                Descrição Arquivo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        <tr
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">A</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">B</td>
                        </tr>

                        <!-- Exemplo de outra linha -->
                        <tr
                            class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900 dark:even:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700/60 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">C</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-200">D</td>
                        </tr>

                        <!-- Empty state (mostre se não houver registros) -->
                        <!--
          <tr>
            <td colspan="2" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
              Nenhum anexo encontrado.
            </td>
          </tr>
          -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</x-app-layout>