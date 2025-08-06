<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemLiberacao extends Model
{
    protected $table = 'itens_liberacao';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'id_item', 'especificado', 'equipamento', 'resultado',
    ];

    public function liberacao()
    {
        return $this->belongsTo(LiberacaoProduto::class, 'id', 'id');
    }

    public function cavidades()
    {
        return $this->hasMany(CavidadeLiberacao::class, ['id', 'id_item'], ['id', 'id_item']);
    }
}