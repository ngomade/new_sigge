<?php

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class CandidatEcole
 *
 * @property string $ca_code
 * @property string $code_ecole
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Candidat $candidat
 * @property Ecole $ecole
 *
 * @package App\Models
 */
class CandidatEcole extends Pivot
{
	protected $table = 'candidat_ecoles';
    protected $fillable = [
        'ca_code',
        'code_ecole',
    ];

	public function candidat(): BelongsToMany
	{
		return $this->belongsToMany(Candidat::class, 'candidat_ecoles', 'ca_code', 'code_ecole')
            ->withPivot('ca_code', 'code_ecole')
            ->withTimestamps();
	}

	public function ecole(): BelongsToMany
	{
		return $this->belongsToMany(Ecole::class, 'candidat_ecoles', 'code_ecole', 'ca_code')
            ->withPivot('ca_code', 'code_ecole')
            ->withTimestamps();
	}
}
