<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PixDetalhe extends Model
{
    use HasFactory;

    protected $table = 'pix_detalhe';
    protected $primaryKey = 'pix_detalhe_id';
    protected $fillable = [
        'venda_id',
        'metodo_pagamento_id',
        'codigo',
        'qr_code',
        'loc_id',
        'tx_id'
    ];

    public $timestamps = false; 

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function meotodoPagamento()
    {
        return $this->belongsTo(MetodoPagamento::class, 'metodo_pagamento_id');
    }
}
