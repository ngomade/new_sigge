<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CentreExaman
 *
 * @property int $centre_exam_code
 * @property string $code_ecole
 * @property string $centre_exam_label
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Ecole $ecole
 *
 * @package App\Models
 */
class CentreExaman extends Model
{
	protected $table = 'centre_examen';
	protected $primaryKey = 'centre_exam_code';

	protected $fillable = [
		'code_ecole',
		'centre_exam_label'
	];

	public function ecole()
	{
		return $this->belongsTo(Ecole::class, 'code_ecole');
	}
}
