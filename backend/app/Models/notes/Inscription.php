<?php


namespace App\Models\notes;

use App\Models\Anneescolaire;
use App\Models\concours\User;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
	protected $table = 'inscription';
	protected $primaryKey = 'code_ins';
	public $incrementing = false;

	protected $casts = [
		'code_annee' => 'int',
		'date_ins' => 'datetime',
		'statut_ins' => 'int'
	];

	protected $fillable = [
        'code_ins',
		'code_user',
		'code_annee',
		'date_ins',
		'statut_ins'
	];

	public function anneescolaire()
	{
		return $this->belongsTo(Anneescolaire::class, 'code_annee');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}

	public function filiere_niveaus()
	{
		return $this->hasMany(FiliereNiveau::class, 'code_ins');
	}

	public function ues()
	{
		return $this->belongsToMany(Ue::class, 'inscription_ue', 'code_ins', 'code_ue')
					->withPivot('etat')
					->withTimestamps();
	}
}
