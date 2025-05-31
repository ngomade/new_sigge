<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MailCandidat
 *
 * @property int $pk_mail_candidat
 * @property string $lca_code
 * @property int $email_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Mail $mail
 * @property Candidat $candidat
 *
 * @package App\Models
 */
class MailCandidat extends Model
{
	protected $table = 'mail_candidat';
	protected $primaryKey = 'pk_mail_candidat';

	protected $casts = [
		'email_code' => 'int'
	];

	protected $fillable = [
		'lca_code',
		'email_code'
	];

	public function mail()
	{
		return $this->belongsTo(Mail::class, 'email_code');
	}

	public function candidat()
	{
		return $this->belongsTo(Candidat::class, 'lca_code');
	}
}
