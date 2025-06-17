<?php

namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;

class Semestre extends Model
{
	protected $table = 'semestre';
	protected $primaryKey = 'code_sem';
	public $incrementing = false;

	protected $fillable = [
        'code_sem',
		'label_sem'
	];

	public function niveaux()
	{
		return $this->belongsToMany(Niveau::class, 'semestre_niveau', 'code_sem', 'code_niveau')
					->withTimestamps();
	}

	public function ues()
	{
		return $this->hasMany(Ue::class, 'code_sem');
	}
}
