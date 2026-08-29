<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class RoleLabo extends Model
{
    protected $table = 'role_labo';

    protected $primaryKey = 'id_rl';

    public $incrementing = true;

    protected $fillable = [
        'id_rl', 'lib_rl',
    ];

    public function affectations()
    {
        return $this->hasMany(LaboratoirePersLab::class, 'id_rl', 'id_rl');
    }
}
