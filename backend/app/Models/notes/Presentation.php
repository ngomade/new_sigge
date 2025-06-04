<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Presentation
 * 
 * @property int $code_pres
 * @property string $code_bureau
 * @property string $photo_chef
 * @property string $message_chef
 * @property string|null $cursus_ing
 * @property string|null $grille_ing
 * @property string|null $science_ing
 * @property string|null $grille_science
 * @property string $nom_chef
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Bureau $bureau
 *
 * @package App\Models\notes
 */
class Presentation extends Model
{
	protected $table = 'presentation';
	protected $primaryKey = 'code_pres';

	protected $fillable = [
		'code_bureau',
		'photo_chef',
		'message_chef',
		'cursus_ing',
		'grille_ing',
		'science_ing',
		'grille_science',
		'nom_chef'
	];

	public function bureau()
	{
		return $this->belongsTo(Bureau::class, 'code_bureau');
	}
}
