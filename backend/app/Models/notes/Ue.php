<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ue
 * 
 * @property string $code_ue
 * @property string $code_sem
 * @property string $intitule_ue
 * @property string|null $desc_ue
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Semestre $semestre
 * @property Collection|Ec[] $ecs
 * @property Collection|Inscription[] $inscriptions
 *
 * @package App\Models\notes
 */
class Ue extends Model
{
	protected $table = 'ue';
	protected $primaryKey = 'code_ue';
	public $incrementing = false;

	protected $fillable = [
		'code_sem',
		'intitule_ue',
		'desc_ue'
	];

	public function semestre()
	{
		return $this->belongsTo(Semestre::class, 'code_sem');
	}

	public function ecs()
	{
		return $this->hasMany(Ec::class, 'code_ue');
	}

	public function inscriptions()
	{
		return $this->belongsToMany(Inscription::class, 'inscription_ue', 'code_ue', 'code_ins')
					->withPivot('etat')
					->withTimestamps();
	}
}
