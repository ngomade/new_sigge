<?php


namespace App\Models\notes;

use App\Models\Bureau;
use App\Models\notes\SessionExamen;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	protected $table = 'documents';
	protected $primaryKey = 'code_doc';

	protected $fillable = [
		'code_session',
		'code_bureau',
		'label_doc',
		'description_doc',
		'type_doc',
		'nom_fichier'
	];

	public function bureau()
	{
		return $this->belongsTo(Bureau::class, 'code_bureau');
	}

	public function sessionExamen()
	{
		return $this->belongsTo(SessionExamen::class, 'code_session');
	}
}
