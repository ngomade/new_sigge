<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SemestreNiveau
 * 
 * @property string $code_niveau
 * @property string $code_sem
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Niveau $niveau
 * @property Semestre $semestre
 *
 * @package App\Models\notes
 */
class SemestreNiveau extends Model
{
	protected $table = 'semestre_niveau';
	public $incrementing = false;

	public function niveau()
	{
		return $this->belongsTo(Niveau::class, 'code_niveau');
	}

	public function semestre()
	{
		return $this->belongsTo(Semestre::class, 'code_sem');
	}
}
