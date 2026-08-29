<?php

namespace App\Models;

use App\Models\notes\Inscription;
use App\Models\notes\SessionExamen;
use Illuminate\Database\Eloquent\Model;

class Anneescolaire extends Model
{
    protected $table = 'anneescolaire';

    protected $primaryKey = 'code_annee';

    public $incrementing = false;

    protected $casts = [
        'code_annee' => 'int',
        'debut_annee' => 'date',
        'fin_annee' => 'date',
    ];

    protected $fillable = [
        'code_annee',
        'debut_annee',
        'fin_annee',
    ];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'code_annee');
    }

    public function session_examen()
    {
        return $this->hasMany(SessionExamen::class, 'code_annee');
    }
}
