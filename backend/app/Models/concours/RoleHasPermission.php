<?php


namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class RoleHasPermission
 *
 * @property string $code_pers
 * @property int $id_role
 * @property Carbon $date_debut
 * @property Carbon $date_fin
 * @property string $statut_role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Personnel $personnel
 * @property Role $role
 *
 * @package App\Models
 */
class RoleHasPermission extends Model
{
	protected $table = 'role_has_permissions';
	public $incrementing = false;

	protected $casts = [
		'id_role' => 'int',
		'date_debut' => 'datetime',
		'date_fin' => 'datetime'
	];

	protected $fillable = [
		'date_debut',
		'date_fin',
		'statut_role'
	];

	public function personnel(): BelongsTo
	{
		return $this->belongsTo(Personnel::class, 'code_pers');
	}

	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class, 'id_role');
	}
}
