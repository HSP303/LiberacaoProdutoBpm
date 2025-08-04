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
                    <div class="flex gap-4 mr-10">
                        <div class="w-1/2">
                            <x-input label="Empresa" name="empresa" type="number" placeholder="Digite a empresa"
                                required="true" />
                        </div>
                        <div class="w-1/2">
                            <x-input label="Produto" name="produto" type="text" placeholder="Digite o Produto" />
                        </div>
                    </div>
                    <hr>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-200">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    File Name
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Description
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Score
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">The Sliding Mr. Bones (Next Stop, Pottersville)</td>
                                <td class="px-6 py-4 whitespace-nowrap">Malcolm Lockyer</td>
                                <td class="px-6 py-4 whitespace-nowrap">1961</td>
                                <td class="px-6 py-4 whitespace-nowrap">1975</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">Witchy Woman</td>
                                <td class="px-6 py-4 whitespace-nowrap">The Eagles</td>
                                <td class="px-6 py-4 whitespace-nowrap">1972</td>
                                <td class="px-6 py-4 whitespace-nowrap">1975</td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">Shining Star</td>
                                <td class="px-6 py-4 whitespace-nowrap">Earth, Wind, and Fire</td>
                                <td class="px-6 py-4 whitespace-nowrap">1975</td>
                                <td class="px-6 py-4 whitespace-nowrap">1975</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>