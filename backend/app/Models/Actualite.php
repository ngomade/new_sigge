<?php


namespace App\Models;

use App\Models\Personnel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actualite extends Model
{
    use HasFactory;
	protected $table = 'actualite';
	protected $primaryKey = 'actu_code';
	public $incrementing = false;
    public $timestamps = true;

	protected $casts = [
		'actu_status' => 'bool',
		'actu_nb_views' => 'int'
	];

	protected $fillable = [
        'actu_code',
		'code_pers',
		'actu_title',
		'actu_content',
		'actu_status',
		'actu_nb_views'
	];

	public function personnel(): BelongsTo
    {
		return $this->belongsTo(Personnel::class, 'code_pers');
	}

	public function ressource_actus()
	{
		return $this->hasMany(RessourceActu::class, 'actu_code');
	}
}
