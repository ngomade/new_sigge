<?php

namespace App\Models;

use App\Models\concours\SessionConcours;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Personnel extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $table = 'personnel';

    protected $primaryKey = 'code_pers';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $casts = [
        'date_naissance_pers' => 'datetime',
        'date_deliv_cni_pers' => 'datetime',
        'nb_enfant_pers' => 'int',
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
        'nb_enfant_pers',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code_pers)) {
                $lastAdmin = Personnel::orderBy('code_pers', 'desc')->first();

                if ($lastAdmin) {
                    // "PERS" fait 4 caractères : on retire ce préfixe pour ne garder que le numéro
                    $lastCode = intval(substr($lastAdmin->code_pers, 4));
                    $nextCode = $lastCode + 1;
                } else {
                    $nextCode = 1;
                }

                $model->code_pers = 'PERS'.str_pad($nextCode, 4, '0', STR_PAD_LEFT);
            }
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
        return $this->hasMany(RoleHasPermission::class, 'code_pers');
    }

    public function sessionconcours(): HasMany
    {
        return $this->hasMany(SessionConcours::class, 'code_pers');
    }

    public function slides(): HasMany
    {
        return $this->hasMany(Slide::class, 'code_pers');
    }

    public function pers_roles()
    {
        return $this->hasMany(PersRole::class, 'code_pers', 'code_pers');
    }
}
