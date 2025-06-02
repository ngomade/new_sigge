<?php

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
class EcoleElement extends Pivot
{
	protected $table = 'ecole_element';

	protected $casts = [
		'code_el' => 'int'
	];

	protected $fillable = [
		'code_ecole',
		'code_el'
	];
}
