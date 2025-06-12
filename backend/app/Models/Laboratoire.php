<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratoire extends Model
{
    use HasFactory;

    protected $table = 'laboratoire';
    protected $primaryKey = 'code_lab';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = [
        'code_lab',
        'label_labo',
        'desc_labo'
    ];

    public function projets()
    {
        return $this->hasMany(ProjetLabo::class, 'code_lab');
    }
}
