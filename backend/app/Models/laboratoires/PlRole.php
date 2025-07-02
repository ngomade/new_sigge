<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class PlRole extends Model
{
    protected $table = 'pers_lab_role';
    public $incrementing = false;
    public $timestamps = true;
    protected $primaryKey = null; // Pas de clé primaire auto-incrément
    protected $fillable = [
        'id_pers_lab', 'id_rl', 'date_debut', 'date_fin'
    ];

    public function persLab()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
    public function roleLabo()
    {
        return $this->belongsTo(RoleLabo::class, 'id_rl', 'id_rl');
    }
}
 