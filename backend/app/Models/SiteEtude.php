<?php

namespace App\Models;

use App\Models\concours\Candidat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteEtude extends Model
{
    use HasFactory;
	protected $table = 'site_etude';
	protected $primaryKey = 'code_site';

	protected $fillable = [
		'label_site',
		'description_site'
	];

	public function candidats(): HasMany
	{
		return $this->hasMany(Candidat::class, 'code_site');
	}
}
