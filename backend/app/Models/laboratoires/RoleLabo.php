<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class RoleLabo extends Model
{
    protected $table = 'role_labo';
    protected $primaryKey = 'id_rl';
    public $incrementing = true;
    protected $fillable = [
        'id_rl', 'lib_rl', 'desc_rl'
    ];

    public function plRoles()
    {
        return $this->hasMany(PlRole::class, 'id_rl', 'id_rl');
    }
}
