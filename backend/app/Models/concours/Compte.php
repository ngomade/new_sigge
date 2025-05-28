<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Compte
 * 
 * @property string $ca_num_recu
 * @property string|null $ca_code
 * @property string $ca_pwd
 * @property string $ca_recu
 * @property string $ca_nom
 * @property string|null $ca_email
 * @property string $ca_prenom
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Candidat|null $candidat
 *
 * @package App\Models
 */
class Compte extends Model
{
	protected $table = 'compte';
	protected $primaryKey = 'ca_num_recu';
	public $incrementing = false;

	protected $fillable = [
		'ca_code',
		'ca_pwd',
		'ca_recu',
		'ca_nom',
		'ca_email',
		'ca_prenom'
	];

	public function candidat()
	{
		return $this->belongsTo(Candidat::class, 'ca_code');
	}
}
