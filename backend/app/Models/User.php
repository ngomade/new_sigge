<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class User
 *
 * @property string $code_user
 * @property int $code_info_extra
 * @property string $nom_user
 * @property string|null $prenom_user
 * @property string $sexe_user
 * @property Carbon $date_naissance_user
 * @property string $lieu_naissance_user
 * @property string $statut_mat_user
 * @property string|null $lieu_resi_user
 * @property string $first_phone_user
 * @property string|null $second_phone_user
 * @property string $numero_cni_user
 * @property string $email_user
 * @property Carbon $date_deliv_cni_user
 * @property string $login_user
 * @property string $pwd_user
 * @property string|null $photo_user
 * @property string|null $handicap_user
 * @property string|null $langue_user
 * @property int $nbre_enfant_user
 * @property string|null $nationalite_user
 * @property string|null $region_origine_user
 * @property string|null $depart_origine_user
 * @property string|null $arrond_origine_user
 * @property string|null $bibiographie_user
 * @property int $statut_user
 * @property string $ecole_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property InfoExtra $info_extra
 * @property Collection|Class[] $classes
 * @property Collection|EtudiantEc[] $etudiant_ecs
 * @property Collection|Evaluation[] $evaluations
 * @property Collection|Inscription[] $inscriptions
 * @property UsersDiplome|null $users_diplome
 * @property UsersRole|null $users_role
 *
 * @package App\Models\notes
 */
class User extends Model
{
	protected $table = 'user';
	protected $primaryKey = 'code_user';
	public $incrementing = false;

	protected $casts = [
		'code_info_extra' => 'int',
		'date_naissance_user' => 'datetime',
		'date_deliv_cni_user' => 'datetime',
		'nbre_enfant_user' => 'int',
		'statut_user' => 'int'
	];

	protected $fillable = [
		'code_info_extra',
		'nom_user',
		'prenom_user',
		'sexe_user',
		'date_naissance_user',
		'lieu_naissance_user',
		'statut_mat_user',
		'lieu_resi_user',
		'first_phone_user',
		'second_phone_user',
		'numero_cni_user',
		'email_user',
		'date_deliv_cni_user',
		'login_user',
		'pwd_user',
		'photo_user',
		'handicap_user',
		'langue_user',
		'nbre_enfant_user',
		'nationalite_user',
		'region_origine_user',
		'depart_origine_user',
		'arrond_origine_user',
		'bibiographie_user',
		'statut_user',
		'ecole_user'
	];

	public function info_extra()
	{
		return $this->belongsTo(InfoExtra::class, 'code_info_extra');
	}

	public function classes()
	{
		return $this->hasMany(Classe::class, 'code_user');
	}

	public function etudiant_ecs()
	{
		return $this->hasMany(EtudiantEc::class, 'code_user');
	}

	public function evaluations()
	{
		return $this->hasMany(Evaluation::class, 'code_user');
	}

	public function inscriptions()
	{
		return $this->hasMany(Inscription::class, 'code_user');
	}

	public function users_diplome()
	{
		return $this->hasOne(UsersDiplome::class, 'code_user');
	}

	public function users_role()
	{
		return $this->hasOne(UsersRole::class, 'code_user');
	}
}
