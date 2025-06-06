<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class InfoExtra
 *
 * @property int $code_info_extra
 * @property string|null $nom_pere_user
 * @property string|null $nom_mere_user
 * @property string|null $telephone_tuteur_user
 * @property string|null $email_tuteur_user
 * @property string|null $telephone_mere
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|User[] $users
 *
 * @package App\Models\notes
 */
class InfoExtra extends Model
{
	protected $table = 'info_extra';
	protected $primaryKey = 'code_info_extra';

	protected $fillable = [
		'nom_pere_user',
		'nom_mere_user',
		'telephone_tuteur_user',
		'email_tuteur_user',
		'telephone_mere'
	];

	public function users()
	{
		return $this->hasMany(User::class, 'code_info_extra');
	}
}
