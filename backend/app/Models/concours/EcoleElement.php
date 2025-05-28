<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EcoleElement
 * 
 * @property int $id
 * @property string $code_ecole
 * @property int $code_el
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ecole $ecole
 * @property Dossier $dossier
 *
 * @package App\Models
 */
class EcoleElement extends Model
{
	protected $table = 'ecole_element';

	protected $casts = [
		'code_el' => 'int'
	];

	protected $fillable = [
		'code_ecole',
		'code_el'
	];

	public function ecole()
	{
		return $this->belongsTo(Ecole::class, 'code_ecole');
	}

	public function dossier()
	{
		return $this->belongsTo(Dossier::class, 'code_el');
	}
}
