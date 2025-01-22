<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalendarioParametro extends Model
{
    use HasFactory;

    protected $table = 'calendario_parametro';
    protected $primaryKey = 'calendario_parametro_id';
    protected $fillable = [
        'empresa_id',
        'dia_semana',
        'status'
    ];

    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
