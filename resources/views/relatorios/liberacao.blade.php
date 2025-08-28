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
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Especificado</th>
                        <th>Equipamento</th>
                        @foreach ($grupo as $cav)
                            <th>{{ $cav->descricao ?? ('Cavidade ' . ($cav->id_cavidade ?? '')) }}</th>
                        @endforeach
                        <th>Resultado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itens as $item)
                        <tr>
                            <td>{{ $item->id_item ?? '' }}</td>
                            <td>{{ $item->especificado ?? '' }}</td>
                            <td>{{ $item->equipamento ?? '' }}</td>
                            @foreach ($grupo as $cav)
                                @php
                                    $cv = $itemCavidade->first(function ($row) use ($item, $cav) {
                                        return ($row->id_item ?? null) == ($item->id_item ?? null)
                                            && ($row->id_cavidade ?? null) == ($cav->id_cavidade ?? null);
                                    });
                                @endphp
                                <td>
                                    @if ($cv)
                                        @if (!is_null($cv->minimo) || !is_null($cv->maximo))
                                            {{ $cv->minimo }}{{ (!is_null($cv->minimo) && !is_null($cv->maximo)) ? ' - ' : '' }}{{ $cv->maximo }}
                                        @else
                                            —
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                            @endforeach
                            <td>{{ $item->resultado ?? '' }}</td>
                        </tr>
                    @endforeach
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
                    <th>NA</th>
                    <th>Obs.</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $checks = [
                        ['Interferência na Montagem', 'ok_interferencia_montagem', 'interferencia_montagem'],
                        ['Folga dos componentes', 'ok_folga_componentes', 'folga_componentes'],
                        ['Teste prático', 'ok_teste_pratico', 'teste_pratico'],
                        ['Aparência (peças/regiões aparentes)', 'ok_aparencia', 'aparencia'],
                        ['Tratamentos conforme especificações', 'ok_tratamentos_especificacoes', 'tratamentos_especificacoes'],
                        ['Teste de queda', 'ok_teste_queda', 'teste_queda'],
                        ['Teste de vida útil', 'ok_teste_vida', 'teste_vida'],
                        ['Outro (5)', 'ok_outro_cinco', 'outro_cinco'],
                        ['Impedir desmontagem', 'ok_impedir_desmontagem', 'impedir_desmontagem'],
                        ['Introdução e funcionamento', 'ok_introducao_funcionamento', 'introducao_funcionamento'],
                        ['Giro livre', 'ok_giro_livre', 'giro_livre'],
                        ['Funcionamento da válvula', 'ok_funcionamento_valvula', 'funcionamento_valvula'],
                        ['Introdução do bocal', 'ok_introducao_bocal', 'introducao_bocal'],
                        ['Retirada do bocal', 'ok_retirada_bocal', 'retirada_bocal'],
                        ['Estanqueidade', 'ok_estanqueidade', 'estanqueidade'],
                        ['Altura conforme requisitos', 'ok_altura_requisitos', 'altura_requisitos'],
                        ['Aparência visual', 'ok_aparencia_visual', 'aparencia_visual'],
                        ['Teste de campo', 'ok_teste_campo', 'teste_campo'],
                        ['Outro (3)', 'ok_outro_tres', 'outro_tres'],
                    ];
                @endphp
                @foreach ($checks as [$label, $okField, $obsField])
                    @php
                        $raw = $lib->{$okField} ?? null; // pode vir como bool, int ou string ('0'/'1')
                        // Normaliza o status para tri-state: true/false/null
                        if (is_string($raw)) {
                            $tmp = trim($raw);
                            if ($tmp === '0' || $tmp === '1') {
                                $raw = (int) $tmp;
                            } elseif ($tmp === '') {
                                $raw = null;
                            }
                        }
                        $isOk = ($raw === true || $raw === 1);
                        $isNao = ($raw === false || $raw === 0);
                        $isNA = is_null($raw);

                        $obs = $lib->{$obsField} ?? '';
                    @endphp
                    @if (!$isNA || !empty($obs))
                        <tr>
                            <td>{{ $label }}</td>
                            <td style="text-align:center;">{{ $isOk ? '☑' : '' }}</td>
                            <td style="text-align:center;">{{ $isNao ? '☑' : '' }}</td>
                            <td style="text-align:center;">{{ $isNA ? '☑' : '' }}</td>
                            <td>{{ $obs }}</td>
                        </tr>
                    @endif
                @endforeach
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
