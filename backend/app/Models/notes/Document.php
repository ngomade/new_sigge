<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use App\Models\Bureau;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Document
 *
 * @property int $code_doc
 * @property string $code_session
 * @property string $code_bureau
 * @property string $label_doc
 * @property string|null $description_doc
 * @property string $type_doc
 * @property string $nom_fichier
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Bureau $bureau
 * @property SessionExamen $session_examan
 *
 * @package App\Models\notes
 */
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
