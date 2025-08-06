<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CavidadeLiberacao extends Model
{
    protected $table = 'cavidades_liberacao';

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'id_item', 'id_cavidade', 'descricao', 'minimo', 'maximo',
    ];

    public function item()
    {
        return $this->belongsTo(ItemLiberacao::class, ['id', 'id_item'], ['id', 'id_item']);
    }
}
