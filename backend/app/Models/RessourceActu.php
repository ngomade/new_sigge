<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RessourceActu
 *
 * @property int $r_id
 * @property string $actu_code
 * @property string $r_type
 * @property string $r_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Actualite $actualite
 *
 * @package App\Models
 */
class RessourceActu extends Model
{
    use HasFactory;
	protected $table = 'ressource_actu';
	protected $primaryKey = 'r_id';
    public $incrementing = true;
	public $timestamps = true;

	protected $fillable = [
        'r_id',
		'actu_code',
		'r_type',
		'r_name'
	];

	public function actualite()
	{
		return $this->belongsTo(Actualite::class, 'actu_code');
	}
}
