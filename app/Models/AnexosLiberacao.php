<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnexosLiberacao extends Model
{
    protected $table = 'anexos_liberacao';
    public $incrementing = false;     // PK composta
    protected $primaryKey = null;     // Laravel ignora, pois PK composta foi criada na migration
    protected $keyType = 'string';

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

    public function setArquivoAttribute($value)
    {
        $this->attributes['arquivo'] = pg_escape_bytea($value);
    }

    public function getArquivoAttribute($value)
    {
        // resource/stream
        if (is_resource($value)) {
            rewind($value);
            return stream_get_contents($value) ?: '';
        }
        // padrão PG: "\xDEADBEEF..."
        if (is_string($value) && strncmp($value, '\\x', 2) === 0) {
            return hex2bin(substr($value, 2)) ?: '';
        }
        // old escape format
        if (is_string($value) && function_exists('pg_unescape_bytea')) {
            $try = @pg_unescape_bytea($value);
            if ($try !== false) return $try;
        }
        // já é bytes puros
        return (string) $value;
    }
}
