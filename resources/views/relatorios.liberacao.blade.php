<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Relatório - Liberação #{{ $lib->id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #222;
        }

        .header {
            margin-bottom: 12px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 6px;
        }

        .muted {
            color: #666;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f3f3f3;
            text-align: left;
        }

        .section {
            margin-top: 14px;
        }

        .right {
            text-align: right;
        }

        .small {
            font-size: 11px;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #777;
        }

        @page {
            margin: 24mm 18mm 18mm;
        }

        /* topo, laterais, base */
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Relatório da Liberação #{{ $lib->id }}</div>
        <div class="muted">Gerado em {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="section">
        <table>
            <tr>
                <th>Cliente</th>
                <td>{{ $lib->cliente->nome ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $lib->status ?? '-' }}</td>
            </tr>
            <tr>
                <th>Data</th>
                <td>{{ optional($lib->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            {{-- adicione aqui os campos relevantes da sua rotina --}}
        </table>
    </div>

    {{-- Exemplo de itens da liberação --}}
    @if (isset($lib->itens) && count($lib->itens))
        <div class="section">
            <strong>Itens</strong>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Descrição</th>
                        <th class="right">Qtd</th>
                        <th class="right">Preço</th>
                        <th class="right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lib->itens as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->descricao }}</td>
                            <td class="right">{{ number_format($item->qtd, 0, ',', '.') }}</td>
                            <td class="right">{{ number_format($item->preco, 2, ',', '.') }}</td>
                            <td class="right">{{ number_format($item->qtd * $item->preco, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Resumo de anexos (sem puxar o bytea) --}}
    @if ($anexos->count())
        <div class="section">
            <strong>Anexos</strong>
            <table>
                <thead>
                    <tr>
                        <th>ID ANX</th>
                        <th>Nome do Arquivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anexos as $ax)
                        <tr>
                            <td>{{ $ax->id_anx }}</td>
                            <td>{{ $ax->nome_arquivo }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="small muted">Obs.: anexos em si não são embutidos; apenas listados.</div>
        </div>
    @endif

    <footer>Página <span class="pageNumber"></span></footer>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 520; $y = 820; // ajuste conforme margem/papel
            $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
            $font = $fontMetrics->get_font("DejaVu Sans", "normal");
            $size = 9;
            $pdf->page_text($x, $y, $text, $font, $size, array(0.45,0.45,0.45));
        }
    </script>
</body>

</html>
