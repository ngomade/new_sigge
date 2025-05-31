<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Mail
 *
 * @property int $email_code
 * @property string $email_objet
 * @property string $email_content
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Candidat[] $candidats
 *
 * @package App\Models
 */
class Mail extends Model
{
	protected $table = 'mails';
	protected $primaryKey = 'email_code';

	protected $fillable = [
		'email_objet',
		'email_content'
	];

	public function candidats()
	{
		return $this->belongsToMany(Candidat::class, 'mail_candidat', 'email_code', 'lca_code')
					->withPivot('pk_mail_candidat')
					->withTimestamps();
	}
}
