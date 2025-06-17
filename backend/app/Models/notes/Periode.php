<?php


namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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
        'code_salle',
        'code_ec',
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
