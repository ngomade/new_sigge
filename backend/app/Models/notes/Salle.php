<?php

namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    protected $table = 'salle';

    protected $primaryKey = 'code_salle';

    public $incrementing = false;

    protected $casts = [
        'nb_place_salle' => 'int',
        'etat_salle' => 'bool',
    ];

    protected $fillable = [
        'code_salle',
        'nb_place_salle',
        'etat_salle',
        'desc_salle',
    ];

    public function periodes()
    {
        return $this->hasMany(Periode::class, 'code_salle');
    }
}
