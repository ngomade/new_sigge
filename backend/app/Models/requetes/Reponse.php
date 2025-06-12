<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reponse
 * 
 * @property string $code_res
 * @property string $text_repone
 * @property string $code_requete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Requete $requete
 *
 * @package App\Models\requetes
 */
class Reponse extends Model
{
	protected $table = 'reponses';
	protected $primaryKey = 'code_res';
	public $incrementing = false;

	protected $fillable = [
		'text_repone',
		'code_requete'
	];

	public function requete()
	{
		return $this->belongsTo(Requete::class, 'code_requete');
	}
}
