<?php

namespace App\Models;


use App\Models\concours\Candidat;
use App\Models\concours\CandidatEcole;
use App\Models\concours\CentreExaman;
use App\Models\concours\Composition;
use App\Models\concours\Dossier;
use App\Models\concours\EcoleElement;
use App\Models\concours\SiteComposition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ecole extends Model
{
    use HasFactory;
	protected $table = 'ecole';
	protected $primaryKey = 'code_ecole';
	public $incrementing = false;

	protected $casts = [
		'centre_depot_code' => 'int'
	];

	protected $fillable = [
		'code_ecole',
		'label_ecole',
		'logo_ecole',
		'desc_ecole',
		'tel_ecole',
		'email_ecole',
		'bp_ecole',
		'centre_depot_code'
	];
	public function centre_depot(): BelongsTo
	{
		return $this->belongsTo(CentreDepot::class, 'centre_depot_code');
	}

	public function candidats(): BelongsToMany
	{
		return $this->belongsToMany(Candidat::class, 'candidat_ecoles', 'code_ecole', 'ca_code')
                    ->using(CandidatEcole::class)
					->withTimestamps();
	}

	public function centre_examen(): HasMany
	{
		return $this->hasMany(CentreExaman::class, 'code_ecole');
	}

	public function site_composition(): belongsToMany
	{
		return $this->belongsToMany(SiteComposition::class, 'composition', 'code_ecole', 'site_code')
            ->withPivot('code_ecole', 'site_code')
            ->using(Composition::class)
            ->withTimestamps();
	}

	public function ecole_elements(): BelongsToMany
	{
		return $this->belongsToMany(Dossier::class, 'ecole_element', 'code_ecole', 'code_el')
            ->using(EcoleElement::class)
            ->withTimestamps();
	}
}
