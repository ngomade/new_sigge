<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CandidatEcole
 * 
 * @property string $ca_code
 * @property string $code_ecole
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Candidat $candidat
 * @property Ecole $ecole
 *
 * @package App\Models
 */
class CandidatEcole extends Model
{
	protected $table = 'candidat_ecoles';
	public $incrementing = false;

	public function candidat()
	{
		return $this->belongsTo(Candidat::class, 'ca_code');
	}

	public function ecole()
	{
		return $this->belongsTo(Ecole::class, 'code_ecole');
	}
}
