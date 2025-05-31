<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

	public function candidat(): BelongsTo
	{
		return $this->belongsTo(Candidat::class, 'ca_code');
	}

	public function ecole(): BelongsTo
	{
		return $this->belongsTo(Ecole::class, 'code_ecole');
	}
}
