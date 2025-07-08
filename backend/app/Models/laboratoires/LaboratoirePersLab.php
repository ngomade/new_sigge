<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class LaboratoirePersLab extends Model
{
    protected $table = 'laboratoire_pers_lab';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'code_lab',
        'id_pers_lab',
        'id_user_externe',
        'id_rl',
        'date_affectation',
        'date_fin_affectation',
        'statut'
    ];

    protected $casts = [
        'date_affectation' => 'date',
        'date_fin_affectation' => 'date'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }

    public function persLab()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }

    public function roleLabo()
    {
        return $this->belongsTo(RoleLabo::class, 'id_rl', 'id_rl');
    }

    public function userExterne()
    {
        return $this->belongsTo(UserExterne::class, 'id_user_externe', 'id_user_ext');
    }
}
