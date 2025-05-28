<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sessionconcour
 * 
 * @property int $id
 * @property string $code_pers
 * @property Carbon $annee
 * @property Carbon $debut
 * @property Carbon $cloture
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Personnel $personnel
 * @property Collection|Candidat[] $candidats
 *
 * @package App\Models
 */
class Sessionconcour extends Model
{
	protected $table = 'sessionconcour';

	protected $casts = [
		'annee' => 'datetime',
		'debut' => 'datetime',
		'cloture' => 'datetime'
	];

	protected $fillable = [
		'code_pers',
		'annee',
		'debut',
		'cloture'
	];

	public function personnel()
	{
		return $this->belongsTo(Personnel::class, 'code_pers');
	}

	public function candidats()
	{
		return $this->hasMany(Candidat::class, 'id');
	}
}
