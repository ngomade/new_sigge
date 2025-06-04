<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FiliereNiveau
 * 
 * @property string $code_niveau
 * @property string $code_ins
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Inscription $inscription
 * @property Niveau $niveau
 *
 * @package App\Models\notes
 */
class FiliereNiveau extends Model
{
	protected $table = 'filiere_niveau';
	public $incrementing = false;

	public function inscription()
	{
		return $this->belongsTo(Inscription::class, 'code_ins');
	}

	public function niveau()
	{
		return $this->belongsTo(Niveau::class, 'code_niveau');
	}
}
