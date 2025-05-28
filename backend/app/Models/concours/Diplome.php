<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Diplome
 * 
 * @property int $code_dip
 * @property string $label_dip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Filiere[] $filieres
 *
 * @package App\Models
 */
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
					->withPivot('id', 'code_serie')
					->withTimestamps();
	}
}
