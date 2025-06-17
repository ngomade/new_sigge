<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use App\Models\Bureau;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Requete extends Model
{
	protected $table = 'requetes';
	protected $primaryKey = 'code_requete';
	public $incrementing = false;

	protected $casts = [
		'date_sousmis' => 'datetime',
		'date_asignation' => 'datetime',
		'date_traitement' => 'datetime'
	];

	protected $fillable = [
		'titre_requete',
		'desc_requete',
		'status',
		'date_sousmis',
		'date_asignation',
		'date_traitement',
		'note_interne',
		'code_cat',
		'code_user',
		'code_bureau'
	];

	public function bureau()
	{
		return $this->belongsTo(Bureau::class, 'code_bureau');
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'code_cat');
	}

	public function user()
	{
		return $this->belongsTo(Users::class, 'code_user');
	}

	public function fichier_requtes()
	{
		return $this->hasMany(FichierRequete::class, 'code_requete');
	}

	public function reponses()
	{
		return $this->hasMany(Reponse::class, 'code_requete');
	}
}
