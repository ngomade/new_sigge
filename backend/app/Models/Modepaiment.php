<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modepaiment extends Model
{
    use HasFactory;
	protected $table = 'modepaiment';
	protected $primaryKey = 'code_mode';
	public $incrementing = false;
    public $timestamps = true;

	protected $casts = [
		'code_mode' => 'int'
	];

	protected $fillable = [
        'code_mode',
		'label_mode',
		'desc_mode'
	];

	public function quitus()
	{
		return $this->hasMany(Quitus::class, 'code_mode');
	}
}
