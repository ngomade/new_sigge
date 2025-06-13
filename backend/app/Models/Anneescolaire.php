<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anneescolaire extends Model
{
	protected $table = 'anneescolaire';
	protected $primaryKey = 'code_annee';
	public $incrementing = false;

	protected $casts = [
		'code_annee' => 'int',
	];

	protected $fillable = [
        'code_annee',
		'debut_annee',
		'fin_annee'
	];

	public function inscriptions()
	{
		return $this->hasMany(Inscription::class, 'code_annee');
	}

	public function session_examen()
	{
		return $this->hasMany(SessionExamen::class, 'code_annee');
	}
}
