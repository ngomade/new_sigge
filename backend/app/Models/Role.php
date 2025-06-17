<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Role extends Model
{
	protected $table = 'roles';
	protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'guard_name'
    ];

	public function permissions()
	{
		return $this->belongsToMany(Permission::class, 'role_has_permission', 'id', 'id_perm')
					->withTimestamps();
	}

	public function role_has_permissions()
	{
		return $this->hasMany(RoleHasPermission::class, 'id');
	}
}
