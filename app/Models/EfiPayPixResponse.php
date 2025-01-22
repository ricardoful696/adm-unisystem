<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EfiPayPixResponse extends Model
{
    use HasFactory;
    
    protected $table = 'efipay_pix_response';
    protected $primaryKey = 'response_id';

    protected $fillable = [
        'venda_id',
        'tx_id',
        'valor',
        'status',
        'loc_id',
        'empresa_pagamento_id'
    ];

    public $timestamps = false;

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }
    public function empresaPagamento()
    {
        return $this->belongsTo(EmpresaPagamento::class, 'empresa_pagamento_id');
    }
}

