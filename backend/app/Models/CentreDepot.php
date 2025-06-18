<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class CentreDepot extends Model
{
	use HasFactory;

	protected $table = 'centre_depot';
	protected $primaryKey = 'centre_depot_code';

	protected $fillable = [
		'centre_depot_code',
		'centre_depot_label'
	];

	public function ecoles(): HasMany
	{
		return $this->hasMany(Ecole::class, 'centre_depot_code');
	}
}
