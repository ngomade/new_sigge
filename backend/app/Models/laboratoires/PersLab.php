<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class PersLab extends Model
{
    protected $table = 'pers_lab';
    protected $primaryKey = 'id_pers_lab';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id_pers_lab', 'type_pers_lab', 'code_lab', 'date_entree', 'date_sortie', 'statut'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
    public function roles()
    {
        return $this->hasMany(PlRole::class, 'id_pers_lab', 'id_pers_lab');
    }
}
