<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocaoNivel extends Model
{
    use HasFactory;

    protected $table = 'promocao_nivel';
    protected $primaryKey = 'promocao_nivel_id';

    protected $fillable = [
        'promocao_id',
        'quantidade_min',
        'quantidade_max',
        'desconto_percentual'
    ];

    public $timestamps = false;

    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }
}
