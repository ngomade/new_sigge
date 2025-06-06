<?php


namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


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
            ->using(Composition::class)
            ->withTimestamps();
	}
}
