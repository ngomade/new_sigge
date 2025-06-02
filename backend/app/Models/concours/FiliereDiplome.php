<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FiliereDiplome
 *
 * @property int $id
 * @property string $filiere_code
 * @property int $code_dip
 * @property int $code_serie
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Diplome $diplome
 * @property Serie $serie
 * @property Filiere $filiere
 *
 * @package App\Models
 */
class FiliereDiplome extends Model
{
	protected $table = 'filiere_diplome';

	protected $casts = [
		'code_dip' => 'int',
		'code_serie' => 'int'
	];

	protected $fillable = [
		'filiere_code',
		'code_dip',
		'code_serie'
	];

	public function diplome()
	{
		return $this->belongsTo(Diplome::class, 'code_dip');
	}

	public function serie()
	{
		return $this->belongsTo(Serie::class, 'code_serie');
	}

	public function filiere()
	{
		return $this->belongsTo(Filiere::class, 'filiere_code');
	}
}
