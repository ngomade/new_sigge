<?php

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Filiere;
use Thiagoprz\CompositeKey\HasCompositeKey;

/**
 * Class FiliereNiveau
 *
 * @property string $code_niveau
 *
 * @property string $code_ins
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Inscription $inscription
 * @property Niveau $niveau
 *
 *
 * @package App\Models\notes
 */
class FiliereNiveau extends Model
{
    use HasCompositeKey;

	protected $table = 'filiere_niveau';
    protected $primaryKey = ['code_filiere', 'code_niveau', 'code_ins'];
	public $incrementing = false;

    protected $fillable = [
        'code_filiere',
        'code_niveau',
        'code_ins'
    ];
	public function inscription()
	{
		return $this->belongsTo(Inscription::class, 'code_ins');
	}

	public function niveau()
	{
		return $this->belongsTo(Niveau::class, 'code_niveau');
	}
	public function filiere()
	{
		return $this->belongsTo(Filiere::class, 'filiere_code');
	}
}
