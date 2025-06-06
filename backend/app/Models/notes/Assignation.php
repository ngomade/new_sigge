<?php


namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use app\Models\Personnel;

/**
 * Class Assignation
 *
 * @property int $code_ass
 * @property string $code_ec
 * @property string $code_class
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
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
		'code_class',
		'code_pers'
	];

	public function class()
	{
		return $this->belongsTo(Classe::class, 'code_class');
	}

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}
	public function personnel()
	{
		return $this->hasMany(Personnel::class, 'code_pers');
	}
}
