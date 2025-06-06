<?php


namespace App\Models;

use App\Models\concours\FiliereDiplome;
use Illuminate\Database\Eloquent\Model;

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
                    ->using(FiliereDiplome::class)
					->withTimestamps();
	}
}
