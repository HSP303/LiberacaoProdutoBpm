<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Liberacao Produtos</title>
</head>
<body>
    <x-navbar />      
    <div class="flex-1 w-1/2">
        <x-select-company/>
        <x-input
            label="Empresa"
            name="empresa"
            type="number"
            placeholder="Digite a empresa"
            required="true"
        />

        <x-input
            label="Produto"
            name="produto"
            type="text"
            placeholder="Digite o Produto"
        />
    </div>

</body>
</html>