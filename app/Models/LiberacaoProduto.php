<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiberacaoProduto extends Model
{
    protected $table = 'liberacao_produtos';

    protected $fillable = [
        'empresa', 'produto', 'fornecedor', 'data_revisao', 'revisao',
        'qtd_avaliada', 'lote', 'data', 'observacao', 'aprovado_reprovado',
        'usuario_analise', 'data_analise',
        // Adicione todos os outros campos se quiser permitir atribuição em massa
    ];

    protected $dates = ['data_revisao', 'data', 'data_analise'];

    public function itens()
    {
        return $this->hasMany(ItemLiberacao::class, 'id', 'id');
    }
}