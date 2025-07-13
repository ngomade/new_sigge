<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaboAnnonce extends Model
{
    protected $table = 'labo_annonces';
    protected $fillable = [
        'code_lab', 'id_admin', 'titre', 'contenu', 'fichier'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(\App\Models\laboratoires\Laboratoire::class, 'code_lab', 'code_lab');
    }

    public function admin()
    {
        return $this->belongsTo(\App\Models\laboratoires\PersLab::class, 'id_admin', 'id_pers_lab');
    }
}
