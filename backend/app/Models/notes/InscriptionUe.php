<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InscriptionUe
 * 
 * @property string $code_ins
 * @property string $code_ue
 * @property int $etat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Inscription $inscription
 * @property Ue $ue
 *
 * @package App\Models\notes
 */
class InscriptionUe extends Model
{
	protected $table = 'inscription_ue';
	public $incrementing = false;

	protected $casts = [
		'etat' => 'int'
	];

	protected $fillable = [
		'etat'
	];

	public function inscription()
	{
		return $this->belongsTo(Inscription::class, 'code_ins');
	}

	public function ue()
	{
		return $this->belongsTo(Ue::class, 'code_ue');
	}
}
