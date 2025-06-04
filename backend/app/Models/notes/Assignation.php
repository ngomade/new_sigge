<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Assignation
 * 
 * @property int $code_ass
 * @property string $code_ec
 * @property string $code_class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Class $class
 * @property Ec $ec
 *
 * @package App\Models\notes
 */
class Assignation extends Model
{
	protected $table = 'assignations';
	protected $primaryKey = 'code_ass';

	protected $fillable = [
		'code_ec',
		'code_class'
	];

	public function class()
	{
		return $this->belongsTo(Class::class, 'code_class');
	}

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}
}
