<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnexosLiberacao extends Model
{
    protected $table = 'anexos_liberacao';

    protected $fillable = [
        'id',
        'id_anx',
        'nome_arquivo',
        'arquivo'
    ];

    public function liberacao()
    {
        return $this->belongsTo(LiberacaoProduto::class, 'id'); // mesma PK
    }
}
