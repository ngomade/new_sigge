<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ressource
 * 
 * @property int $code_res
 * @property string $label_res
 * @property string|null $code_ec
 * @property string $type_res
 * @property string|null $desc_res
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Ec[] $ecs
 *
 * @package App\Models\notes
 */
class Ressource extends Model
{
	protected $table = 'ressource';
	protected $primaryKey = 'code_res';

	protected $fillable = [
		'label_res',
		'code_ec',
		'type_res',
		'desc_res'
	];

	public function ecs()
	{
		return $this->belongsToMany(Ec::class, 'ec_ressource', 'code_res', 'code_ec')
					->withTimestamps();
	}
}
