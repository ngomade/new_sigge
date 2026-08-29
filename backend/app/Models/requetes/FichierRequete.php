<?php

namespace App\Models\requetes;

use Illuminate\Database\Eloquent\Model;

class FichierRequete extends Model
{
    protected $table = 'fichier_requetes';

    protected $primaryKey = 'id_fichier';

    public $incrementing = false;

    protected $fillable = [
        'id_fichier',
        'chemin',
        'code_requete',
    ];

    public function requete()
    {
        return $this->belongsTo(Requete::class, 'code_requete');
    }
}
