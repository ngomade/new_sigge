<?php


namespace App\Models;

use App\Models\notes\Ec;
use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
	protected $table = 'ressource';
	protected $primaryKey = 'code_res';

	protected $fillable = [
		'label_res',
		'code_ec',
		'type_res',
		'desc_res'
	];

	public function ecs()
	{
		return $this->belongsToMany(Ec::class, 'ec_ressource', 'code_res', 'code_ec')
					->withTimestamps();
	}
}
