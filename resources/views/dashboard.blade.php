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
                    <div class="overflow-auto max-w-full border border-gray-300 rounded-md">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th
                                        class="sticky left-0 z-20 bg-gray-200 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        File Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Description</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Score</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Genre</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duration</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rating</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Language</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Release Date</th>
                                    <th
                                        class="sticky right-0 z-20 bg-gray-200 px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producer</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap">The Sliding Mr.
                                        Bones (Next Stop, Pottersville)</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Malcolm Lockyer</td>
                                    <td class="px-6 py-4 whitespace-nowrap">1961</td>
                                    <td class="px-6 py-4 whitespace-nowrap">View</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Jazz</td>
                                    <td class="px-6 py-4 whitespace-nowrap">3:45</td>
                                    <td class="px-6 py-4 whitespace-nowrap">4.5</td>
                                    <td class="px-6 py-4 whitespace-nowrap">English</td>
                                    <td class="px-6 py-4 whitespace-nowrap">1961-07-15</td>
                                    <td class="sticky right-0 z-10 bg-white px-6 py-4 whitespace-nowrap">EMI</td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap">Witchy Woman
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">The Eagles</td>
                                    <td class="px-6 py-4 whitespace-nowrap">1972</td>
                                    <td class="px-6 py-4 whitespace-nowrap">View</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Rock</td>
                                    <td class="px-6 py-4 whitespace-nowrap">4:10</td>
                                    <td class="px-6 py-4 whitespace-nowrap">4.7</td>
                                    <td class="px-6 py-4 whitespace-nowrap">English</td>
                                    <td class="px-6 py-4 whitespace-nowrap">1972-11-08</td>
                                    <td class="sticky right-0 z-10 bg-white px-6 py-4 whitespace-nowrap">Asylum Records</td>
                                </tr>
                                <tr>
                                    <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap">Shining Star
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">Earth, Wind, and Fire</td>
                                    <td class="px-6 py-4 whitespace-nowrap">1975</td>
                                    <td class="px-6 py-4 whitespace-nowrap">View</td>
                                    <td class="px-6 py-4 whitespace-nowrap">Funk</td>
                                    <td class="px-6 py-4 whitespace-nowrap">3:20</td>
                                    <td class="px-6 py-4 whitespace-nowrap">4.8</td>
                                    <td class="px-6 py-4 whitespace-nowrap">English</td>
                                    <td class="px-6 py-4 whitespace-nowrap">1975-05-21</td>
                                    <td class="sticky right-0 z-10 bg-white px-6 py-4 whitespace-nowrap">Columbia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>