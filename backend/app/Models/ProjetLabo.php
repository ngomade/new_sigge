<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjetLabo extends Model
{
    use HasFactory;

    protected $table = 'projet_labo';
    protected $primaryKey = 'code_projet';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'code_projet',
        'theme_projet',
        'description_projet',
        'code_lab'
    ];

}
