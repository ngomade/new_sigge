<?php



namespace App\Models\notes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


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
	public function sousBureau(){
		return $this->belongsToMany(Bureau::class, 'sous_bureau', 'code_bureau', 'code_sous_bureau');
	}
	public function bureauParents(){
		return $this->belongsToMany(Bureau::class, 'sous_bureau', 'code_sous_bureau', 'code_bureau');
	}

}
