<?php

namespace App\Models\concours;

use Carbon\Carbon;

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
}
