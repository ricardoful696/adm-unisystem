<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocaoCompraAntecipada extends Model
{
    use HasFactory;

    protected $table = 'promocao_compra_antecipada';
    protected $primaryKey = 'promocao_compra_antecipada_id';

    protected $fillable = [
        'promocao_id',
        'dias_antecedencia_min',
        'dias_antecedencia_max',
        'desconto_percentual'
    ];

    public $timestamps = false;

    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }
}
