<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidat extends Model
{
    protected $table = 'candidat';
    protected $primaryKey = 'ca_code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ca_code',
        'id',
        'filiere_code',
        'code_site',
        'ca_nom',
        'ca_prenom',
        'ca_sexe',
        'ca_date_naiss',
        'ca_lieu_naiss',
        'ca_statut_mat',
        'ca_adresse',
        'ca_telephone',
        'ca_num_cni',
        'ca_email',
        'ca_premiere_lang',
        'ca_nationalite',
        'ca_region_origine',
        'ca_depart_origine',
        'ca_diplome_admission',
        'ca_annee_diplome',
        'ca_serie_diplome',
        'ca_mention_diplome',
        'ca_etab_diplome',
        'ca_pays_diplome',
        'ca_centre_examen',
        'ca_centre_depot',
        'ca_nom_pere',
        'ca_telephone_pere',
        'ca_nom_mere',
        'ca_telephone_mere',
        'ca_handicap',
        'ca_email_pere',
        'ca_deliv_cni',
        'ca_num_recu',
        'ca_recu'
    ];

    /**
     * Get the site d'étude that owns the candidat.
     */
    public function site_etude(): BelongsTo
    {
        return $this->belongsTo(SiteEtude::class, 'code_site', 'code_site');
    }

    /**
     * Get the filière that owns the candidat.
     */
    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_code', 'filiere_code');
    }

    /**
     * Get the session concours that owns the candidat.
     */
    public function sessionconcour(): BelongsTo
    {
        return $this->belongsTo(SessionConcour::class, 'id', 'id');
    }

    /**
     * Get the comptes for the candidat.
     */
    public function comptes(): HasMany
    {
        return $this->hasMany(Compte::class, 'ca_code', 'ca_code');
    }
} 