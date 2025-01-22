<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendario extends Model
{
    use HasFactory;

    protected $table = 'calendario';
    protected $primaryKey = 'calendario_id';
    protected $fillable = [
        'empresa_id',
        'data',
        'status',
        'feriado',
        'dia_semana'
    ];

    public $timestamps = false;

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}
