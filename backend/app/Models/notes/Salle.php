<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Salle
 * 
 * @property string $code_salle
 * @property int $nb_place_salle
 * @property bool $etat_salle
 * @property string|null $desc_salle
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Periode[] $periodes
 *
 * @package App\Models\notes
 */
class Salle extends Model
{
	protected $table = 'salle';
	protected $primaryKey = 'code_salle';
	public $incrementing = false;

	protected $casts = [
		'nb_place_salle' => 'int',
		'etat_salle' => 'bool'
	];

	protected $fillable = [
		'nb_place_salle',
		'etat_salle',
		'desc_salle'
	];

	public function periodes()
	{
		return $this->hasMany(Periode::class, 'code_salle');
	}
}
