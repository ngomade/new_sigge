<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Periode
 * 
 * @property string $code_salle
 * @property string $code_ec
 * @property int|null $code_periode
 * @property Carbon $debut_periode
 * @property int $jour_periode
 * @property Carbon $fin_periode
 * @property int $duree_periode
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ec $ec
 * @property Salle $salle
 *
 * @package App\Models\notes
 */
class Periode extends Model
{
	protected $table = 'periode';
	public $incrementing = false;

	protected $casts = [
		'code_periode' => 'int',
		'debut_periode' => 'datetime',
		'jour_periode' => 'int',
		'fin_periode' => 'datetime',
		'duree_periode' => 'int'
	];

	protected $fillable = [
		'code_periode',
		'debut_periode',
		'jour_periode',
		'fin_periode',
		'duree_periode'
	];

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}

	public function salle()
	{
		return $this->belongsTo(Salle::class, 'code_salle');
	}
}
