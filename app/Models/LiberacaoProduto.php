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
        'Ok_interferencia_montagem',
        'Ok_folga_componentes',
        'Ok_teste_pratico',
        'Ok_aparencia',
        'Ok_tratamentos_especificacoes',
        'Ok_teste_queda',
        'Ok_teste_vida',
        'Ok_outro_cinco',
        'Ok_impedir_desmontagem',
        'Ok_introducao_funcionamento',
        'Ok_giro_livre',
        'Ok_funcionamento_valvula',
        'Ok_introducao_bocal',
        'Ok_retirada_bocal',
        'Ok_estanqueidade',
        'Ok_altura_requisitos',
        'Ok_aparencia_visual',
        'Ok_teste_campo',
        'Ok_outro_tres',
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
