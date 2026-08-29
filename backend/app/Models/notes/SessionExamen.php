<?php

namespace App\Models\notes;

use App\Models\Anneescolaire;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionExamen extends Model
{
    protected $table = 'session_examen';

    protected $primaryKey = 'code_session';

    public $incrementing = false;

    protected $casts = [
        'code_annee' => 'int',
        'date_debut_session' => 'datetime',
        'date_fin_session' => 'datetime',
        'statut_session' => 'int',
    ];

    protected $fillable = [
        'code_session',
        'code_annee',
        'label_session',
        'date_debut_session',
        'date_fin_session',
        'statut_session',
    ];

    public function anneescolaire(): BelongsTo
    {
        return $this->belongsTo(Anneescolaire::class, 'code_annee');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'code_session');
    }

    public function examen()
    {
        return $this->hasMany(Examen::class, 'code_session');
    }
}
