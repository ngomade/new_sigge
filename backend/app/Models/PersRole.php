<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;
use Spatie\Permission\Models\Role;
class PersRole extends Model
{
    use HasCompositeKey;
    protected $table = 'pers_role';
    protected $primaryKey = ['code_bureau', 'code_pers', 'id'];
    public $incrementing = false;
    public $timestamps = true;

    protected $casts = [
        'id' => 'int',
        'date_debut_role' => 'datetime',
        'date_fin_role' => 'date',
        'statut_role' => 'int'
    ];

    protected $fillable = [
        'id',
        'code_bureau',
        'code_pers',
        'date_debut_role',
        'date_fin_role',
        'statut_role'
    ];

    // Constantes pour le statut du rôle
    const STATUT_ACTIF = 1;
    const STATUT_INACTIF = 0;
    const STATUT_EXPIRE = 2;

    // Scopes pour faciliter les requêtes
    public function scopeActif($query)
    {
        return $query->where('statut_role', self::STATUT_ACTIF)
                    ->where(function($q) {
                        $q->whereNull('date_fin_role')
                          ->orWhere('date_fin_role', '>', now());
                    });
    }

    public function scopeExpire($query)
    {
        return $query->where('statut_role', self::STATUT_ACTIF)
                    ->where('date_fin_role', '<', now());
    }

    public function isActif()
    {
        return $this->statut_role === self::STATUT_ACTIF &&
               ($this->date_fin_role === null || $this->date_fin_role > now());
    }

    public function isExpire()
    {
        return $this->statut_role === self::STATUT_ACTIF &&
               $this->date_fin_role !== null &&
               $this->date_fin_role < now();
    }

    public function bureau()
    {
        return $this->belongsTo(Bureau::class, 'code_bureau');
    }

    public function personnel()
    {
        return $this->belongsTo(Personnel::class, 'code_pers');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id', 'id');
    }
}
