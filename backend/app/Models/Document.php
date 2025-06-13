<?php


namespace App\Models;

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

	public function session_examan()
	{
		return $this->belongsTo(SessionExaman::class, 'code_session');
	}
}
