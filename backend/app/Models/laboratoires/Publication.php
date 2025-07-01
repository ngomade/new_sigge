<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    protected $table = 'publication';
    protected $primaryKey = 'code_publi';
    public $incrementing = true;
    protected $fillable = [
        'code_publi', 'titre_publi', 'type_publi', 'date_publi', 'domaine', 'resume', 'id_pers_lab'
    ];

    public function createur()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
}
