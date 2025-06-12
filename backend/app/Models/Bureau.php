<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bureau extends Model
{
	protected $table = 'bureau';
	protected $primaryKey = 'code_bureau';
	public $incrementing = false;

	protected $fillable = [
		'code_bureau',
		'label_bureau',
		'desc_bureau',
		'type_bureau'
	];

	public function documents()
	{
		return $this->hasMany(Document::class, 'code_bureau');
	}

	public function presentations()
	{
		return $this->hasMany(Presentation::class, 'code_bureau');
	}
	public function sousBureau(): BelongsToMany
    {
		return $this->belongsToMany(Bureau::class, 'sous_bureau', 'code_bureau', 'code_sous_bureau');
	}
	public function bureauParents(): BelongsToMany
    {
		return $this->belongsToMany(Bureau::class, 'sous_bureau', 'code_sous_bureau', 'code_bureau');
	}
    public function filieres()
    {
        return $this->hasMany(Filiere::class, 'code_bureau');
    }
}
