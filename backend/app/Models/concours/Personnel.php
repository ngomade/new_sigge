<?php

namespace App\Models\concours;

use App\Models\concours\RoleHasPermission;
use App\Models\concours\Sessionconcour;
use App\Models\concours\Slide;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Personnel extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasFactory, HasApiTokens;

	protected $table = 'personnel';
	protected $primaryKey = 'code_pers';
	public $incrementing = false;

	protected $casts = [
		'date_naissance_pers' => 'datetime',
		'date_deliv_cni_pers' => 'datetime',
		'nb_enfant_pers' => 'int'
	];

	protected $fillable = [
        'code_pers',
		'nom_pers',
		'prenom_pers',
		'sexe_pers',
		'date_naissance_pers',
		'lieu_naissance_pers',
		'statut_mat_pers',
		'lieu_residence_pers',
		'first_phone_pers',
		'second_phone_pers',
		'cni_pers',
		'date_deliv_cni_pers',
		'email_pers',
        'email_verified_at',
        'reset_token',
        'reset_token_expires_at',
		'login_pers',
		'pwd_pers',
		'photo_pers',
		'lang_pers',
		'nationalite_pers',
		'region_pers',
		'depart_pers',
		'arrond_pers',
		'bibliographie_pers',
		'nb_enfant_pers'
	];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function () {
            $lastAdmin = Personnel::orderBy('code_pers', 'desc')->first();

            if ($lastAdmin) {
                $lastCode = intval(substr($lastAdmin->code_pers, 2)); // Extract the numeric part
                $nextCode = $lastCode + 1;
            } else {
                $nextCode = 1;
            }

            return 'PERS' . str_pad($nextCode, 4, '0', STR_PAD_LEFT);
        });
    }
    public function getAuthPassword(): string
    {
        return $this->pwd_pers;
    }

    public function routeNotificationForMail(): string
    {
        return $this->email_pers;
    }

	public function role_has_permissions(): HasMany
	{
		return $this->hasMany(\App\Models\concours\RoleHasPermission::class, 'code_pers');
	}

	public function sessionconcours(): HasMany
	{
		return $this->hasMany(\App\Models\concours\Sessionconcour::class, 'code_pers');
	}

	public function slides(): HasMany
	{
		return $this->hasMany(\App\Models\concours\Slide::class, 'code_pers');
	}
}
