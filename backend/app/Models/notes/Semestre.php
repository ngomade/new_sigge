<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Semestre
 * 
 * @property string $code_sem
 * @property string|null $label_sem
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Niveau[] $niveaux
 * @property Collection|Ue[] $ues
 *
 * @package App\Models\notes
 */
class Semestre extends Model
{
	protected $table = 'semestre';
	protected $primaryKey = 'code_sem';
	public $incrementing = false;

	protected $fillable = [
		'label_sem'
	];

	public function niveaux()
	{
		return $this->belongsToMany(Niveau::class, 'semestre_niveau', 'code_sem', 'code_niveau')
					->withTimestamps();
	}

	public function ues()
	{
		return $this->hasMany(Ue::class, 'code_sem');
	}
}
