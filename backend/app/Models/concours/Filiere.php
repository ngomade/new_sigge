<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use App\Models\concours\Diplome;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Filiere
 *
 * @property string $filiere_code
 * @property string $filiere_label
 * @property string|null $filiere_description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Candidat[] $candidats
 * @property Collection|Diplome[] $diplomes
 *
 * @package App\Models
 */
class Filiere extends Model
{
    use HasFactory;

	protected $table = 'filiere';
	protected $primaryKey = 'filiere_code';
	public $incrementing = false;

	protected $fillable = [
		'filiere_code',
		'filiere_label',
		'filiere_description'
	];

	public function candidats()
	{
		return $this->hasMany(Candidat::class, 'filiere_code');
	}

	public function diplomes()
	{
		return $this->belongsToMany(Diplome::class, 'filiere_diplome', 'filiere_code', 'code_dip')
					->withPivot('id', 'code_serie')
					->withTimestamps();
	}
}
