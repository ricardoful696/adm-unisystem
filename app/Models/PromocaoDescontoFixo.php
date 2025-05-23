<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocaoDescontoFixo extends Model
{
    use HasFactory;

    protected $table = 'promocao_desconto_fixo';
    protected $primaryKey = 'promocao_desconto_fixo_id';

    protected $fillable = [
        'promocao_id',
        'quantidade_min',
        'desconto_percentual'
    ];

    public $timestamps = false;

    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }
}
