<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusPagamentoDescricao extends Model
{
    use HasFactory;

    protected $table = 'status_pagamento_descricao';
    protected $primaryKey = 'status_pagamento_descricao_id';
    protected $fillable = [
        'descricao'
    ];

    public $timestamps = false; 

    public function statusPagamento()
    {
        return $this->hasMany(StatusPagamento::class, 'status_pagamento_descricao_id', 'status_pagamento_descricao_id');
    }

    public function venda()
    {
        return $this->hasMany(Venda::class, 'status_pagamento_descricao_id', 'status_pagamento_descricao_id');
    }
}
