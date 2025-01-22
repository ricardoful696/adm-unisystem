<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteParametro extends Model
{
    use HasFactory;

    protected $table = 'lote_parametro';
    protected $primaryKey = 'lote_parametro_id';
    protected $fillable = [
        'data_inicio',
        'data_final',
        'lote_id',
        'qtd_venda',
        'qtd_venda_realizada',
        'ativo',
        'lote_numero'
        
    ];

    public $timestamps = false; 

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

   
    
}
