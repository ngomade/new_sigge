<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Composition
 * 
 * @property string $code_ecole
 * @property string $site_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ecole $ecole
 * @property SiteComposition $site_composition
 *
 * @package App\Models
 */
class Composition extends Model
{
	protected $table = 'composition';
	public $incrementing = false;

	public function ecole()
	{
		return $this->belongsTo(Ecole::class, 'code_ecole');
	}

	public function site_composition()
	{
		return $this->belongsTo(SiteComposition::class, 'site_code');
	}
}
