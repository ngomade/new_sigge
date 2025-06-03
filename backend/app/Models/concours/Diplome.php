<?php


namespace App\Models\concours;

use Illuminate\Database\Eloquent\Model;
use App\Models\concours\Filiere;

class Diplome extends Model
{
	protected $table = 'diplome';
	protected $primaryKey = 'code_dip';

	protected $fillable = [
		'label_dip'
	];

	public function filieres()
	{
		return $this->belongsToMany(Filiere::class, 'filiere_diplome', 'code_dip', 'filiere_code')
					->withPivot('code_serie')
					->withTimestamps();
	}
}
