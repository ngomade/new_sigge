<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class SiteComposition
 *
 * @property string $site_code
 * @property string $site_ville
 * @property string $site_lieu
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Composition[] $compositions
 *
 * @package App\Models
 */
class SiteComposition extends Model
{
	protected $table = 'site_composition';
	protected $primaryKey = 'site_code';
	public $incrementing = false;

	protected $fillable = [
		'site_code',
		'site_ville',
		'site_lieu',
	];

	public function ecoles(): BelongsToMany
	{
		return $this->belongsToMany(Ecole::class, 'composition', 'site_code', 'code_ecole')
            ->withPivot('code_ecole', 'site_code')
            ->withTimestamps();
	}
}
