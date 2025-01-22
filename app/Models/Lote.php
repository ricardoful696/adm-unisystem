<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lote';
    protected $primaryKey = 'lote_id';
    protected $fillable = [
        'empresa_id',
        'nome',
        'ativo',
        'lote_por_qtd',
        'lote_por_data',
        'tipo_desconto_id',
        'valor_desconto'
    ];

    public $timestamps = false; 

    public function loteParametro()
    {
        return $this->hasMany(LoteParametro::class, 'lote_id');
    }

    public function loteProduto()
    {
        return $this->hasMany(LoteProduto::class, 'lote_id');
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function tipoDesconto()
    {
        return $this->belongsTo(TipoDesconto::class, 'tipo_desonto_id');
    }
}
