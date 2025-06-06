<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Niveau
 * 
 * @property string $code_niveau
 * @property string|null $label_niveau
 * @property string $code_class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Class $class
 * @property Collection|FiliereNiveau[] $filiere_niveaus
 * @property Collection|Semestre[] $semestres
 *
 * @package App\Models\notes
 */
class Niveau extends Model
{
	protected $table = 'niveau';
	protected $primaryKey = 'code_niveau';
	public $incrementing = false;

	protected $fillable = [
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
