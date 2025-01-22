<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campanha extends Model
{
    use HasFactory;

    protected $table = 'campanha';
    protected $primaryKey = 'campanha_id';
    protected $fillable = [
        'empresa_id',
        'usuario_id',
        'data_criacao',
        'data_inicio',
        'data_final',
        'tipo_desconto_id',
        'valor_desconto',
        'status',
        'nome',
        'qtd_cupons',
        'qtd_digitos_cupom',
        'sigla_inicial_cupom',
        'valor_min_compras',
        'valor_max_desconto',
        'qtd_uso_cupom',
        'limite_max_diario',
        'contador_limite_diario',
        'data_ultimo_reset',
    ];

    public $timestamps = false; 

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function cupom()
    {
        return $this->hasMany(Cupom::class, 'campanha_id');
    }

    public function tipoDesconto()
    {
        return $this->belongsTo(TipoDesconto::class, 'tipo_desconto_id');
    }
}
