<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Class
 * 
 * @property string $code_class
 * @property string $label_class
 * @property string $code_user
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User $user
 * @property Collection|Assignation[] $assignations
 * @property Collection|Niveau[] $niveaux
 *
 * @package App\Models\notes
 */
class Class extends Model
{
	protected $table = 'classes';
	protected $primaryKey = 'code_class';
	public $incrementing = false;

	protected $fillable = [
		'label_class',
		'code_user'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}

	public function assignations()
	{
		return $this->hasMany(Assignation::class, 'code_class');
	}

	public function niveaux()
	{
		return $this->hasMany(Niveau::class, 'code_class');
	}
}
