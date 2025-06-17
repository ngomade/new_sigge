<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use App\Models\concours\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
        'code_ec',
        'code_examen',
        'code_user',
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
		return $this->belongsTo(Examen::class, 'code_examen');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}
}
