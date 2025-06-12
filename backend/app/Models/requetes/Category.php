<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * 
 * @property string $code_cat
 * @property string $label_cat
 * @property string $desc_cat
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|Requete[] $requetes
 *
 * @package App\Models\requetes
 */
class Category extends Model
{
	protected $table = 'categories';
	protected $primaryKey = 'code_cat';
	public $incrementing = false;

	protected $fillable = [
		'label_cat',
		'desc_cat'
	];

	public function requetes()
	{
		return $this->hasMany(Requete::class, 'code_cat');
	}
}
