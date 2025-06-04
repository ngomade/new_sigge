<?php

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Ecole
 *
 * @property string $code_ecole
 * @property string $label_ecole
 * @property string $logo_ecole
 * @property string $desc_ecole
 * @property string $tel_ecole
 * @property string|null $email_ecole
 * @property string $bp_ecole
 * @property int $centre_depot_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CentreDepot $centre_depot
 * @property Collection|Candidat[] $candidats
 * @property Collection|CentreExaman[] $centre_examen
 * @property Collection|Composition[] $compositions
 * @property Collection|EcoleElement[] $ecole_elements
 *
 * @package App\Models
 */
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
