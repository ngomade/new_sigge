<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Dossier
 *
 * @property int $code_el
 * @property string $label_el
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|EcoleElement[] $ecole_elements
 *
 * @package App\Models
 */
class Dossier extends Model
{
	protected $table = 'dossier';
	protected $primaryKey = 'code_el';

	protected $fillable = [
		'label_el'
	];

	public function ecole_elements()
	{
		return $this->hasMany(EcoleElement::class, 'code_el');
	}
}
