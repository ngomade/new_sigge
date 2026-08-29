<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersRole extends Model
{
    protected $table = 'users_role';

    protected $primaryKey = ['code_user', 'id'];

    public $incrementing = false;

    protected $casts = [
        // 'annee_dip' => 'datetime',
        'date_debut_role' => 'datetime',
        'date_fin_role' => 'datetime',
        'etat_role' => 'int',
    ];

    protected $fillable = [
        // 'annee_dip',
        'date_debut_role',
        'date_fin_role',
        'etat_role',
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'code_user');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id');
    }
}
