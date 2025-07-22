<?php


namespace App\Models\concours;


use App\Models\Ecole;
use App\Models\Filiere;
use App\Models\SiteEtude;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;


class Candidat extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'candidat';
    protected $primaryKey = 'ca_code';
    public $incrementing = false;

    protected $casts = [
        'id' => 'int',
        'code_site' => 'int',
        'ca_date_naiss' => 'datetime',
    ];

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

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($model) {
            do {
                $id = Str::upper(Str::substr($model->filiere_code, 0, 2));
                $id .= rand(1000, 10000);
            } while (Candidat::where('ca_code', $id)->exists());
            $model->ca_code = $id;
        });
    }

    public function site_etude(): BelongsTo
    {
        return $this->belongsTo(SiteEtude::class, 'code_site');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_code', 'code_filiere');
    }

    public function sessionconcour(): BelongsTo
    {
        return $this->belongsTo(SessionConcours::class, 'id');
    }

    public function ecoles(): BelongsToMany
    {
        return $this->belongsToMany(Ecole::class, 'candidat_ecoles', 'ca_code', 'code_ecole')
            ->using(CandidatEcole::class)
            ->withTimestamps();
    }

    public function comptes(): HasMany
    {
        return $this->hasMany(Compte::class, 'ca_code');
    }

    public function mails(): BelongsToMany
    {
        return $this->belongsToMany(Mail::class, 'mail_candidat', 'lca_code', 'email_code')
            ->withPivot('pk_mail_candidat')
            ->withTimestamps();
    }
    
}
