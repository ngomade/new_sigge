<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EcRessource
 * 
 * @property string $code_ec
 * @property int $code_res
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ec $ec
 * @property Ressource $ressource
 *
 * @package App\Models\notes
 */
class EcRessource extends Model
{
	protected $table = 'ec_ressource';
	public $incrementing = false;

	protected $casts = [
		'code_res' => 'int'
	];

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}

	public function ressource()
	{
		return $this->belongsTo(Ressource::class, 'code_res');
	}
}
