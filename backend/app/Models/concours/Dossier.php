<?php


namespace App\Models\concours;

use App\Models\concours\Ecole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dossier extends Model
{
	protected $table = 'dossier';
	protected $primaryKey = 'code_el';

	protected $fillable = [
		'label_el'
	];

	public function ecole_elements(): BelongsToMany
	{
		return $this->belongsToMany(Ecole::class, 'ecole_element', 'code_el', 'code_ecole')
            ->using(EcoleElement::class)
            ->withTimestamps();
	}
}
