<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FichierRequte
 * 
 * @property string $id_fichier
 * @property string $chemin
 * @property string $code_requete
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Requete $requete
 *
 * @package App\Models\requetes
 */
class FichierRequte extends Model
{
	protected $table = 'fichier_requtes';
	protected $primaryKey = 'id_fichier';
	public $incrementing = false;

	protected $fillable = [
		'chemin',
		'code_requete'
	];

	public function requete()
	{
		return $this->belongsTo(Requete::class, 'code_requete');
	}
}
