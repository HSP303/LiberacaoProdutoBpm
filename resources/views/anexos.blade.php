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
                            <form method="POST" action="{{ route('anexos.store', $liberacao->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div>
                                    <div>
                                        <x-input-label for="descricao_arquivo" :value="__('Descrição do Arquivo')" />
                                        <x-text-input id="descricao_arquivo" class="block mt-1" style="width:100%;"
                                            type="text" name="descricao_arquivo" :value="old('descricao_arquivo')"
                                            required autofocus autocomplete="representado" />
                                        <x-input-error :messages="$errors->get('descricao_arquivo')" class="mt-2" />
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 mt-2">
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

    <div class="container">
        <div class="py-3">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="relative">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-black dark:text-gray-400">
                                    <thead class="text-xs text-black uppercase rounded-lg"
                                        style="background-color: #3b82f6; border-radius: 5px;">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Sequencia</th>
                                            <th scope="col" class="px-6 py-3">Descrição Arquivo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100">
                                            <td class="px-6 py-4">A</td>
                                            <td class="px-6 py-4">B</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>