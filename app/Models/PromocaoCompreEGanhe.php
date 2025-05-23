<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromocaoCompreEGanhe extends Model
{
    use HasFactory;

    protected $table = 'promocao_compre_e_ganhe';
    protected $primaryKey = 'promocao_compre_e_ganhe_id';

    protected $fillable = [
        'promocao_id',
        'produto_id',
        'quantidade_compra',
        'quantidade_gratis',
        'produto_id_gratis'
    ];

    public $timestamps = false;

    public function promocao()
    {
        return $this->belongsTo(Promocao::class, 'promocao_id');
    }
}
