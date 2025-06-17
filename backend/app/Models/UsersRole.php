<?php


namespace App\Models;

use App\Models\concours\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UsersRole
 *
 * @property string $code_user
 * @property Carbon $annee_dip
 * @property Carbon $date_debut_role
 * @property Carbon|null $date_fin_role
 * @property int $etat_role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 *
 * @package App\Models\notes
 */
class UsersRole extends Model
{
	protected $table = 'users_role';
	protected $primaryKey = ['code_user','id'];
	public $incrementing = false;

	protected $casts = [
		// 'annee_dip' => 'datetime',
		'date_debut_role' => 'datetime',
		'date_fin_role' => 'datetime',
		'etat_role' => 'int'
	];

	protected $fillable = [
		// 'annee_dip',
		'date_debut_role',
		'date_fin_role',
		'etat_role'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}
	public function role()
	{
		return $this->belongsTo(Role::class, 'id');
	}
}
