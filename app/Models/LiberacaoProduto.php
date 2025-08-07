<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiberacaoProduto extends Model
{
    protected $table = 'liberacao_produtos';

    protected $fillable = [
        'empresa',
        'produto',
        'fornecedor',
        'data_revisao',
        'revisao',
        'qtd_avaliada',
        'lote',
        'data',
        'interferencia_montagem',
        'folga_componentes',
        'teste_pratico',
        'aparencia',
        'tratamentos_especificacoes',
        'teste_queda',
        'teste_vida',
        'outro_cinco',
        'impedir_desmontagem',
        'introducao_funcionamento',
        'giro_livre',
        'funcionamento_valvula',
        'introducao_bocal',
        'retirada_bocal',
        'estanqueidade',
        'altura_requisitos',
        'aparencia_visual',
        'teste_campo',
        'outro_tres',
        'observacao',
        'aprovado_reprovado',
        'usuario_analise',
        'data_analise',
        'ok_interferencia_montagem',
        'ok_folga_componentes',
        'ok_teste_pratico',
        'ok_aparencia',
        'ok_tratamentos_especificacoes',
        'ok_teste_queda',
        'ok_teste_vida',
        'ok_outro_cinco',
        'ok_impedir_desmontagem',
        'ok_introducao_funcionamento',
        'ok_giro_livre',
        'ok_funcionamento_valvula',
        'ok_introducao_bocal',
        'ok_retirada_bocal',
        'ok_estanqueidade',
        'ok_altura_requisitos',
        'ok_aparencia_visual',
        'ok_teste_campo',
        'ok_outro_tres',
    ];

    protected $dates = [
        'data_revisao',
        'data',
        'data_analise',
    ];

    // Exemplo de relacionamento com os itens
    public function itens()
    {
        return $this->hasMany(ItemLiberacao::class, 'id', 'id');
    }
}
