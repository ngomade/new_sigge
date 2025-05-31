<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SiteEtude
 *
 * @property int $code_site
 * @property string $label_site
 * @property string $description_site
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Candidat[] $candidats
 *
 * @package App\Models
 */
class SiteEtude extends Model
{
    use HasFactory;
	protected $table = 'site_etude';
	protected $primaryKey = 'code_site';

	protected $fillable = [
		'label_site',
		'description_site'
	];

	public function candidats()
	{
		return $this->hasMany(Candidat::class, 'code_site');
	}
}
