<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SessionExaman
 * 
 * @property string $code_session
 * @property int $code_annee
 * @property string $label_session
 * @property Carbon $date_debut_session
 * @property Carbon|null $date_fin_session
 * @property int $statut_session
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Anneescolaire $anneescolaire
 * @property Collection|Document[] $documents
 * @property Collection|Examan[] $examen
 *
 * @package App\Models\notes
 */
class SessionExamen extends Model
{
	protected $table = 'session_examen';
	protected $primaryKey = 'code_session';
	public $incrementing = false;

	protected $casts = [
		'code_annee' => 'int',
		'date_debut_session' => 'datetime',
		'date_fin_session' => 'datetime',
		'statut_session' => 'int'
	];

	protected $fillable = [
		'code_annee',
		'label_session',
		'date_debut_session',
		'date_fin_session',
		'statut_session'
	];

	public function anneescolaire()
	{
		return $this->belongsTo(Anneescolaire::class, 'code_annee');
	}

	public function documents()
	{
		return $this->hasMany(Document::class, 'code_session');
	}

	public function examen()
	{
		return $this->hasMany(Examen::class, 'code_session');
	}
}
