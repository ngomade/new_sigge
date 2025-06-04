<?php

namespace App\Models\concours;

use App\Models\concours\Candidat;
use App\Models\concours\Personnel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
