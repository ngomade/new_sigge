<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use App\Models\Bureau;
use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


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

     protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($model) {
            do {
                $id = 'REQ-' . strtoupper(Str::random(10));
            } while (Requete::where('code_requete', $id)->exists());
            $model->code_requete = $id;
        });
    }

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
		return $this->belongsTo(Users::class, 'code_user');
	}

	public function fichiers()
	{
		return $this->hasMany(FichierRequete::class, 'code_requete');
	}

	public function reponses()
	{
		return $this->hasMany(Reponse::class, 'code_requete');
	}
}
