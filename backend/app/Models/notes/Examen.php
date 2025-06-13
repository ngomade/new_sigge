<?php


namespace App\Models\notes;

use App\Models\SessionExamen;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Examan
 *
 * @property string $code_examen
 * @property string $code_session
 * @property string $type_evaluation
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property SessionExamen $session_examen
 * @property Collection|Evaluation[] $evaluations
 *
 * @package App\Models\notes
 */
class Examen extends Model
{
	protected $table = 'examen';
	protected $primaryKey = 'code_examen';
	public $incrementing = false;

	protected $fillable = [
		'code_session',
		'type_evaluation'
	];

	public function session_examan()
	{
		return $this->belongsTo(SessionExamen::class, 'code_session');
	}

	public function evaluations()
	{
		return $this->hasMany(Evaluation::class, 'code_examen');
	}
}
