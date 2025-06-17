<?php



namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
	protected $table = 'ue';
	protected $primaryKey = 'code_ue';
	public $incrementing = false;

	protected $fillable = [
        'code_ue',
		'code_sem',
		'intitule_ue',
		'desc_ue'
	];

	public function semestre()
	{
		return $this->belongsTo(Semestre::class, 'code_sem');
	}

	public function ecs()
	{
		return $this->hasMany(Ec::class, 'code_ue');
	}

	public function inscriptions()
	{
		return $this->belongsToMany(Inscription::class, 'inscription_ue', 'code_ue', 'code_ins')
					->withPivot('etat')
					->withTimestamps();
	}
}
