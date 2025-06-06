<?php


namespace App\Models\notes;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inscription
 *
 * @property string $code_ins
 * @property string $code_user
 * @property int $code_annee
 * @property Carbon $date_ins
 * @property int $statut_ins
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Anneescolaire $anneescolaire
 * @property User $user
 * @property Collection|FiliereNiveau[] $filiere_niveaus
 * @property Collection|Ue[] $ues
 *
 * @package App\Models\notes
 */
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
