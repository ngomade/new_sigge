<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Serie
 *
 * @property int $code_serie
 * @property string $label_serie
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|FiliereDiplome[] $filiere_diplomes
 *
 * @package App\Models
 */
class Serie extends Model
{
	protected $table = 'serie';
	protected $primaryKey = 'code_serie';

	protected $fillable = [
		'label_serie'
	];

	public function filiere_diplomes()
	{
		return $this->hasMany(FiliereDiplome::class, 'code_serie');
	}
}
