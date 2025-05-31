<?php


namespace App\Models\concours;

use App\Models\concours\Candidat;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Compte extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;
	protected $table = 'compte';
	protected $primaryKey = 'ca_num_recu';
    protected $keyType = 'string';
    public $incrementing = false;

	protected $fillable = [
        'ca_num_recu',
		'ca_code',
		'ca_pwd',
		'ca_recu',
        'email_verified_at',
        'reset_token',
        'reset_token_expires_at',
		'ca_nom',
		'ca_email',
		'ca_prenom'
	];
    public function getAuthPassword(): string
    {
        return $this->ca_pwd;
    }
    public function routeNotificationForMail(): string
    {
        return $this->ca_email;
    }
    public function candidat(): BelongsTo
	{
		return $this->belongsTo(Candidat::class, 'ca_code');
	}
}
