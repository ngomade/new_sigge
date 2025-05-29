<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionConcour extends Model
{
    protected $table = 'sessionconcour';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'id',
        'annee',
        'date_debut',
        'date_fin',
        'statut'
    ];

    /**
     * Get the candidats for the session concours.
     */
    public function candidats(): HasMany
    {
        return $this->hasMany(Candidat::class, 'id', 'id');
    }
} 