<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;

class PersRole extends Model
{
    use HasCompositeKey;
    protected $table = 'pers_role';
    protected $primaryKey = ['code_bureau', 'code_pers', 'code_role'];
    public $incrementing = false;
    public $timestamps = true;

    protected $casts = [
        'code_role' => 'int',
        'date_debut_role' => 'datetime',
        'date_fin_role' => 'date',
        'satut_role' => 'int'
    ];

    protected $fillable = [
        'code_role',
        'code_bureau',
        'code_pers',
        'date_debut_role',
        'date_fin_role',
        'satut_role'
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
        return $this->satut_role === self::STATUT_ACTIF &&
               ($this->date_fin_role === null || $this->date_fin_role > now());
    }

    public function isExpire()
    {
        return $this->satut_role === self::STATUT_ACTIF &&
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
        return $this->belongsTo(Role::class, 'code_role', 'id');
    }
}
