<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Slide extends Model
{
    use HasFactory;
	protected $table = 'slide';

	protected $fillable = [
        'code_pers',
		'first_title',
		'second_title',
		'photo'
	];

	public function personnel(): BelongsTo
    {
		return $this->belongsTo(Personnel::class, 'code_pers');
	}
}
