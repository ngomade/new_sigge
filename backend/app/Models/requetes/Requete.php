<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Requete
 * 
 * @property string $code_requete
 * @property string $titre_requete
 * @property string $desc_requete
 * @property string $status
 * @property Carbon $date_sousmis
 * @property Carbon $date_asignation
 * @property Carbon $date_traitement
 * @property string $note_interne
 * @property string $code_cat
 * @property string $code_user
 * @property string $code_bureau
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Bureau $bureau
 * @property Category $category
 * @property User $user
 * @property Collection|FichierRequte[] $fichier_requtes
 * @property Collection|Reponse[] $reponses
 *
 * @package App\Models\requetes
 */
class Requete extends Model
{
	protected $table = 'requetes';
	protected $primaryKey = 'code_requete';
	public $incrementing = false;

	protected $casts = [
		'date_sousmis' => 'datetime',
		'date_asignation' => 'datetime',
		'date_traitement' => 'datetime'
	];

	protected $fillable = [
		'titre_requete',
		'desc_requete',
		'status',
		'date_sousmis',
		'date_asignation',
		'date_traitement',
		'note_interne',
		'code_cat',
		'code_user',
		'code_bureau'
	];

	public function bureau()
	{
		return $this->belongsTo(Bureau::class, 'code_bureau');
	}

	public function category()
	{
		return $this->belongsTo(Category::class, 'code_cat');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}

	public function fichier_requtes()
	{
		return $this->hasMany(FichierRequte::class, 'code_requete');
	}

	public function reponses()
	{
		return $this->hasMany(Reponse::class, 'code_requete');
	}
}
