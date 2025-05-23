<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interno extends Model
{
    use HasFactory;

    protected $table = 'interno';
    protected $primaryKey = 'interno_id';
    protected $fillable = [
        'dominio'
    ];

    public $timestamps = false; 
}
