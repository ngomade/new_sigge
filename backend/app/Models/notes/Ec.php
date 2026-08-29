<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use App\Models\Ressource;
use Illuminate\Database\Eloquent\Model;

class Ec extends Model
{
    protected $table = 'ec';

    protected $primaryKey = 'code_ec';

    public $incrementing = false;

    protected $casts = [
        'credit_ec' => 'int',
        'vh_ec' => 'int',
        'cm_ec' => 'int',
        'td_ec' => 'int',
        'tp_ec' => 'int',
        'tpe_ec' => 'int',
    ];

    protected $fillable = [
        'code_ec',
        'code_ue',
        'intitule_ec',
        'credit_ec',
        'vh_ec',
        'cm_ec',
        'td_ec',
        'tp_ec',
        'tpe_ec',
    ];

    public function ue()
    {
        return $this->belongsTo(Ue::class, 'code_ue');
    }

    public function assignations()
    {
        return $this->hasMany(Assignation::class, 'code_ec');
    }

    public function ressources()
    {
        return $this->belongsToMany(Ressource::class, 'ec_ressource', 'code_ec', 'code_res')
            ->withTimestamps();
    }

    public function etudiant_ecs()
    {
        return $this->hasMany(EtudiantEc::class, 'code_ec');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'code_ec');
    }

    public function periodes()
    {
        return $this->hasMany(Periode::class, 'code_ec');
    }
}
