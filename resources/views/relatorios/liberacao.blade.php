<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Relatório de Liberação de Produto</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
        }

        th {
            background: #f3f3f3;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
            margin-bottom: 5px;
        }

        .section {
            margin-top: 12px;
        }

        .cav-table {
            margin-bottom: 10px;
        }

        .anexo {
            margin-top: 30px;
            page-break-before: always;
        }

        .anexo img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    {{-- Cabeçalho --}}
    <div class="header">
        <img src="https://media.licdn.com/dms/image/v2/C4D0BAQEmC35X1LOodg/company-logo_200_200/company-logo_200_200/0/1632589914374?e=2147483647&v=beta&t=RY9IWYGY8pK5O_4Jfcuy9XZUmJ7sRzVBR_TOs8CwbeM"
            class="logo">
        <h2>Relatório de Liberação do Produto</h2>
    </div>

    {{-- Dados principais --}}
    <table>
        <tr>
            <th>Descrição do comp/produto</th>
            <td>{{ $lib->produto ?? '' }}</td>
            <th>Código</th>
            <td>{{ $lib->codigo ?? '' }}</td>
            <th>Data da rev.</th>
            <td>{{ 'Rev '. $lib->revisao . ' - ' . $lib->data_revisao ?? '' }}</td>
        </tr>
        <tr>
            <th>Fornecedor ou processo interno</th>
            <td>{{ $lib->fornecedor ?? '' }}</td>
            <th>Qtd. Avaliada</th>
            <td>{{ $lib->qtd_avaliada ?? '' }}</td>
            <th>Lote</th>
            <td>{{ $lib->lote ?? '' }}</td>
        </tr>
        <tr>
            <th>Realizado por</th>
            <td>{{ $lib->responsavel ?? '' }}</td>
            <th>Data</th>
            <td colspan="3">{{ $lib->created_at ?? '' }}</td>
        </tr>
    </table>

    {{-- Tabela Itens / Cavidades --}}
    <div class="section">
        <h3>Parâmetros indicados <strong>(F e FC)</strong> no desenho</h3>
        @foreach ($cavidades->chunk(5) as $grupo)
            <table class="cav-table">
                <p>teste</p>
                <thead>
                    <tr>
                        <th>Especificado</th>
                        @foreach ($grupo as $cav)
                            <th>{{ $cav->nome ?? 'Cavidade' }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $item->especificado ?? '' }}</td>
                        @foreach ($grupo as $cav)
                            <td>{{ $cav->valor ?? '' }}</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @endforeach
    </div>

    {{-- Check List de testes --}}
    <div class="section">
        <h3>Check List para Testes Funcionais</h3>
        <table>
            <thead>
                <tr>
                    <th>Verificar</th>
                    <th>Ok</th>
                    <th>Não ok</th>
                    <th>Obs.</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Interferência na Montagem</td>
                </tr>
                <tr>
                    <td>Teste prático</td>
                </tr>
                <tr>
                    <td>Aparência (peças/regiões aparentes)</td>
                </tr>
                <tr>
                    <td>Teste de vida útil</td>
                   <!-- <td>{{ $check['vida_ok'] ?? '' }}</td> -->
                </tr>
            </tbody>
        </table>
    </div>

    {{-- Observações --}}
    <div class="section">
        <h3>Observações</h3>
        <p>{{ $lib->observacoes ?? '' }}</p>
    </div>

    {{-- Laudo final --}}
    <div class="section">
        <table>
            <tr>
                <th>Laudo Final / Qualidade</th>
                <td>
                    @if ($lib->status == 'aprovado')
                        ☑ Aprovado
                    @endif
                    @if ($lib->status == 'condicional')
                        ☑ Aprovado Condicional
                    @endif
                    @if ($lib->status == 'reprovado')
                        ☑ Reprovado
                    @endif
                </td>
                <th>Analisado por</th>
                <td>{{ $lib->responsavel ?? '' }}</td>
            </tr>
        </table>
    </div>

    {{-- Anexos --}}
    <div class="section">
        <h3>Anexos</h3>
        @foreach ($anexos as $ax)
            <div class="anexo">
                <h4>{{ $ax->nome_arquivo }}</h4>

                @php
                    $mime = $ax->mime ?? '';
                @endphp

                @if (Str::startsWith($mime, 'image/'))
                    <img src="data:{{ $mime }};base64,{{ base64_encode($ax->arquivo) }}">
                @elseif($mime === 'application/pdf')
                    <object data="data:application/pdf;base64,{{ base64_encode($ax->arquivo) }}" type="application/pdf"
                        width="100%" height="800">
                        PDF não pôde ser exibido.
                    </object>
                @else
                    <p>[Arquivo não exibível]</p>
                @endif
            </div>
        @endforeach
    </div>

</body>

</html>
