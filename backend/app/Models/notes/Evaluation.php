<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Evaluation
 * 
 * @property string $code_ec
 * @property string $code_examen
 * @property string $code_user
 * @property Carbon $date_evaluation
 * @property string|null $code_ano
 * @property float $note_eval
 * @property Carbon $date_evalu
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ec $ec
 * @property Examan $examan
 * @property User $user
 *
 * @package App\Models\notes
 */
class Evaluation extends Model
{
	protected $table = 'evaluation';
	public $incrementing = false;

	protected $casts = [
		'date_evaluation' => 'datetime',
		'note_eval' => 'float',
		'date_evalu' => 'datetime'
	];

	protected $fillable = [
		'date_evaluation',
		'code_ano',
		'note_eval',
		'date_evalu'
	];

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}

	public function examan()
	{
		return $this->belongsTo(Examan::class, 'code_examen');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}
}
