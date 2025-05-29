<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filiere extends Model
{
    protected $table = 'filiere';
    protected $primaryKey = 'filiere_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'filiere_code',
        'nom',
        'description'
    ];

    /**
     * Get the candidats for the filière.
     */
    public function candidats(): HasMany
    {
        return $this->hasMany(Candidat::class, 'filiere_code', 'filiere_code');
    }
} 