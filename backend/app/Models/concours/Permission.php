<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission
 *
 * @property int $id_perm
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Role[] $roles
 *
 * @package App\Models
 */
class Permission extends Model
{
	protected $table = 'permissions';
	protected $primaryKey = 'id_perm';

	public function roles()
	{
		return $this->belongsToMany(Role::class, 'role_has_permission', 'id_perm', 'id_role')
					->withTimestamps();
	}
}
