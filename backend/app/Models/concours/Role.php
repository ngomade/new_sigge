<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property int $id_role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Permission[] $permissions
 * @property Collection|RoleHasPermission[] $role_has_permissions
 *
 * @package App\Models
 */
class Role extends Model
{
	protected $table = 'roles';
	protected $primaryKey = 'id_role';

	public function permissions()
	{
		return $this->belongsToMany(Permission::class, 'role_has_permission', 'id_role', 'id_perm')
					->withTimestamps();
	}

	public function role_has_permissions()
	{
		return $this->hasMany(RoleHasPermission::class, 'id_role');
	}
}
