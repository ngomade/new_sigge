<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Anneescolaire
 * 
 * @property int $code_annee
 * @property Carbon $debut_annee
 * @property Carbon $fin_annee
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Inscription[] $inscriptions
 * @property Collection|SessionExaman[] $session_examen
 *
 * @package App\Models\notes
 */
class Anneescolaire extends Model
{
	protected $table = 'anneescolaire';
	protected $primaryKey = 'code_annee';
	public $incrementing = false;

	protected $casts = [
		'code_annee' => 'int',
		'debut_annee' => 'dateTime',
		'fin_annee' => 'dateTime'
	];

	protected $fillable = [
		'debut_annee',
		'fin_annee'
	];

	public function inscriptions()
	{
		return $this->hasMany(Inscription::class, 'code_annee');
	}

	public function session_examen()
	{
		return $this->hasMany(SessionExamen::class, 'code_annee');
	}
}
