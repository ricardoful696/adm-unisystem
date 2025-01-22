<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodoPagamento extends Model
{
    use HasFactory;

    protected $table = 'metodo_pagamento';
    protected $primaryKey = 'metodo_pagamento_id';
    protected $fillable = [
        'descricao'
    ];

    public $timestamps = false; 

    public function venda()
    {
        return $this->hasMany(Venda::class, 'metodo_pagamento_id', 'metodo_pagamento_id');
    }
}
