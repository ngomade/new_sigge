<?php


namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
class Composition extends Pivot
{
	protected $table = 'composition';

    protected $fillable = [
        "code_ecole",
        "site_code"
    ];
}
