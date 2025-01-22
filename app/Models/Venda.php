<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $table = 'venda';
    protected $primaryKey = 'venda_id';
    protected $fillable = [
        'usuario_id',
        'empresa_id',
        'tx_id',
        'valor',
        'chave_pix',
        'loc_id',
        'data',
        'status_pagamento_descricao_id',
        'metodo_pagamento_id',
        'empresa_pagamento_id',
        'cupom_id',
        'valor_desconto',
        'saldo_utilizado',
        'total'
    ];

    public $timestamps = false; 

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function vendaProduto()
    {
        return $this->hasMany(VendaProduto::class, 'venda_id');
    }
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function metodoPagamento()
    {
        return $this->belongsTo(MetodoPagamento::class, 'metodo_pagamento_id');
    }

    public function empresaPagamento()
    {
        return $this->belongsTo(EmpresaPagamento::class, 'empresa_pagamento_id');
    }

    public function statusPagamento()
    {
        return $this->belongsTo(StatusPagamento::class, 'status_pagamento_id');
    }


    public function statusPagamentoDescricao()
    {
        return $this->belongsTo(StatusPagamentoDescricao::class, 'status_pagamento_descricao_id');
    }
}
