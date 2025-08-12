<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemCavidadeLiberacao extends Model
{
    protected $table = 'itens_cavidade_liberacao';

    // Como usamos chave composta, desativamos o incremento
    public $incrementing = false;

    // Tipo da chave primária
    protected $keyType = 'string';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'id',
        'id_item',
        'id_cavidade',
        'minimo',
        'maximo',
    ];

    // Desativando timestamps automáticos
    public $timestamps = false;

    /**
     * Relação com liberacao_produtos
     */
    public function liberacaoProduto()
    {
        return $this->belongsTo(LiberacaoProduto::class, 'id', 'id');
    }

    /**
     * Relação com itens_liberacao
     */
    public function itemLiberacao()
    {
        return $this->belongsTo(ItemLiberacao::class, ['id', 'id_item'], ['id', 'id_item']);
    }

    /**
     * Relação com cavidades_liberacao
     */
    public function cavidadeLiberacao()
    {
        return $this->belongsTo(CavidadeLiberacao::class, ['id', 'id_cavidade'], ['id', 'id_cavidade']);
    }
}
