<?php


namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;


class Niveau extends Model
{
	protected $table = 'niveau';
	protected $primaryKey = 'code_niveau';
	public $incrementing = false;

	protected $fillable = [
        'code_niveau',
		'label_niveau',
		'code_class'
	];

	public function class()
	{
		return $this->belongsTo(Classe::class, 'code_class');
	}

	public function filiere_niveaus()
	{
		return $this->hasMany(FiliereNiveau::class, 'code_niveau');
	}

	public function semestres()
	{
		return $this->belongsToMany(Semestre::class, 'semestre_niveau', 'code_niveau', 'code_sem')
					->withTimestamps();
	}
}
