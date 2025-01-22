<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPagamento extends Model
{
    use HasFactory;

    protected $table = 'status_pagamento';
    protected $primaryKey = 'status_pagamento_id';
    protected $fillable = [
        'venda_id',
        'status_pagamento_descricao_id',
        'data',
        'hora'
    ];

    public $timestamps = false; 

    public function venda()
    {
        return $this->belongsTo(Venda::class, 'venda_id');
    }

    public function statusPagamentoDescricao()
    {
        return $this->belongsTo(StatusPagamentoDescricao::class, 'status_pagamento_descricao_id');
    }
}
