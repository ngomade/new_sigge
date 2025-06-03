<?php

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class CentreDepot
 *
 * @property int $centre_depot_code
 * @property string $centre_depot_label
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Ecole[] $ecoles
 *
 * @package App\Models
 */
class CentreDepot extends Model
{
	use HasFactory;

	protected $table = 'centre_depot';
	protected $primaryKey = 'centre_depot_code';

	protected $fillable = [
		'centre_depot_code',
		'centre_depot_label'
	];

	public function ecoles(): HasMany
	{
		return $this->hasMany(Ecole::class, 'centre_depot_code');
	}
}
