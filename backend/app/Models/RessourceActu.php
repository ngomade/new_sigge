<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RessourceActu extends Model
{
    use HasFactory;
	protected $table = 'ressource_actu';
	protected $primaryKey = 'r_id';

	protected $fillable = [
        'r_id',
		'actu_code',
		'r_type',
		'r_name'
	];

	public function actualite()
	{
		return $this->belongsTo(Actualite::class, 'actu_code');
	}
}
