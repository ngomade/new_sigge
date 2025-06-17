<?php

namespace App\Models\requetes;

use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    protected $table = "reponses";
    protected $primaryKey = "code_res";
    public $incrementing = false;
    protected $fillable = [
        "code_res",
        "text_reponse",
        'code_requete',
    ];

	public function requete()
	{
		return $this->belongsTo(Requete::class, 'code_requete');
	}
}
