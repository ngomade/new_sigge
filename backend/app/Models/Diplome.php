<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diplome extends Model
{
    use HasFactory;
	protected $table = 'diplome';
	protected $primaryKey = 'code_dip';

	protected $fillable = [
		'label_dip',
        'specialite_dip',
	];

	public function filieres()
	{
		return $this->belongsToMany(Filiere::class, 'filiere_diplome', 'code_dip', 'code_filiere')
            ->using(FiliereDiplome::class)
            ->withPivot('code_serie')
            ->withTimestamps();
	}

    public function series()
    {
        return $this->belongsToMany(Serie::class, 'filiere_diplome', 'code_dip', 'code_serie')
            ->using(FiliereDiplome::class)
            ->withPivot('code_filiere')
            ->withTimestamps();
    }
}
