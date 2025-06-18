<?php


namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SemestreNiveau extends Model
{
	protected $table = 'semestre_niveau';
	public $incrementing = false;

    protected $fillable = [
        'code_niveau',
        'code_sem'
    ];

	public function niveau()
	{
		return $this->belongsTo(Niveau::class, 'code_niveau');
	}

	public function semestre()
	{
		return $this->belongsTo(Semestre::class, 'code_sem');
	}
}
