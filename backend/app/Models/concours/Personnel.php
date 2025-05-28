<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Personnel
 * 
 * @property string $code_pers
 * @property string $nom_pers
 * @property string|null $prenom_pers
 * @property string $sexe_pers
 * @property Carbon $date_naissance_pers
 * @property string $lieu_naissance_pers
 * @property string $statut_mat_pers
 * @property string|null $lieu_residence_pers
 * @property string $first_phone_pers
 * @property string|null $second_phone_pers
 * @property string $cni_pers
 * @property Carbon $date_deliv_cni_pers
 * @property string $email_pers
 * @property string $login_pers
 * @property string $pwd_pers
 * @property string|null $photo_pers
 * @property string|null $lang_pers
 * @property string|null $nationalite_pers
 * @property string|null $region_pers
 * @property string|null $depart_pers
 * @property string|null $arrond_pers
 * @property string|null $bibliographie_pers
 * @property int|null $nb_enfant_pers
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|RoleHasPermission[] $role_has_permissions
 * @property Collection|Sessionconcour[] $sessionconcours
 * @property Collection|Slide[] $slides
 *
 * @package App\Models
 */
class Personnel extends Model
{
	protected $table = 'personnel';
	protected $primaryKey = 'code_pers';
	public $incrementing = false;

	protected $casts = [
		'date_naissance_pers' => 'datetime',
		'date_deliv_cni_pers' => 'datetime',
		'nb_enfant_pers' => 'int'
	];

	protected $fillable = [
		'nom_pers',
		'prenom_pers',
		'sexe_pers',
		'date_naissance_pers',
		'lieu_naissance_pers',
		'statut_mat_pers',
		'lieu_residence_pers',
		'first_phone_pers',
		'second_phone_pers',
		'cni_pers',
		'date_deliv_cni_pers',
		'email_pers',
		'login_pers',
		'pwd_pers',
		'photo_pers',
		'lang_pers',
		'nationalite_pers',
		'region_pers',
		'depart_pers',
		'arrond_pers',
		'bibliographie_pers',
		'nb_enfant_pers'
	];

	public function role_has_permissions()
	{
		return $this->hasMany(RoleHasPermission::class, 'code_pers');
	}

	public function sessionconcours()
	{
		return $this->hasMany(Sessionconcour::class, 'code_pers');
	}

	public function slides()
	{
		return $this->hasMany(Slide::class, 'code_pers');
	}
}
