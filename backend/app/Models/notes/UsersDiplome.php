<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Diplome;

/**
 * Class UsersDiplome
 * 
 * @property string $code_user
 * @property Carbon $annee_dip
 * @property string $institution_dip
 * @property string $mention_dip
 * @property string $pays_dip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 *
 * @package App\Models\notes
 */
class UsersDiplome extends Model
{
	protected $table = 'users_diplome';
	protected $primaryKey = ['code_user','code_dip'];
	public $incrementing = false;

	protected $casts = [
		'annee_dip' => 'datetime'
	];

	protected $fillable = [
		'annee_dip',
		'institution_dip',
		'mention_dip',
		'pays_dip'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}
	public function diplome()
	{
		return $this->belongsTo(Diplome::class, 'code_dip');
	}
}
