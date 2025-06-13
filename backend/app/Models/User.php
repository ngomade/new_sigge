<?php

namespace App\Models;

use App\Models\notes\EtudiantEc;
use App\Models\notes\Evaluation;
use App\Models\notes\UsersRole;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $table = 'users';
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
        "code_user",
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
