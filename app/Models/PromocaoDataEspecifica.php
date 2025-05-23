<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocaoDataEspecifica extends Model
{
    use HasFactory;

    protected $table = 'promocao_data_especifica';
    protected $primaryKey = 'promocao_data_especifica_id';

    protected $fillable = [
        'promocao_id',
        'data_especifica',
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
