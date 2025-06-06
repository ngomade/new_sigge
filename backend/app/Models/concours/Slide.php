<?php


namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slide
 *
 * @property int $id_slide
 * @property string $first_title
 * @property string $second_title
 * @property string $photo
 * @property string $code_pers
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Personnel $personnel
 *
 * @package App\Models
 */
class Slide extends Model
{
	protected $table = 'slide';
	protected $primaryKey = 'id_slide';

	protected $fillable = [
		'first_title',
		'second_title',
		'photo',
		'code_pers'
	];

	public function personnel()
	{
		return $this->belongsTo(Personnel::class, 'code_pers');
	}
}
