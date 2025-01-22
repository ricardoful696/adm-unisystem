<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    
    use HasFactory;

    protected $table = 'carteira';
    protected $primaryKey = 'carteira_id';
    protected $fillable = [
        'usuario_id',
        'saldo'
    ];

    public $timestamps = false; 

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
    public function carteiraMovimentacao()
    {
        return $this->hasMany(CarteiraMovimentacao::class, 'carteira_id');
    }

}
