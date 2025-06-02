<?php

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Sessionconcour
 *
 * @property int $id
 * @property string $code_pers
 * @property Carbon $annee
 * @property Carbon $debut
 * @property Carbon $cloture
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Personnel $personnel
 * @property Collection|Candidat[] $candidats
 *
 * @package App\Models
 */
class Sessionconcour extends Model
{
    use HasFactory;
	protected $table = 'sessionconcour';

	protected $casts = [
		'debut' => 'datetime',
		'cloture' => 'datetime'
	];

	protected $fillable = [
		'code_pers',
		'annee',
		'debut',
		'cloture'
	];

	public function personnel(): BelongsTo
	{
		return $this->belongsTo(Personnel::class, 'code_pers');
	}

	public function candidats(): HasMany
	{
		return $this->hasMany(Candidat::class, 'id');
	}
}
