<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EtudiantEc
 * 
 * @property string $code_user
 * @property string $code_ec
 * 
 * @property Ec $ec
 * @property User $user
 *
 * @package App\Models\notes
 */
class EtudiantEc extends Model
{
	protected $table = 'etudiant_ec';
	public $incrementing = false;
	public $timestamps = false;

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}
}
