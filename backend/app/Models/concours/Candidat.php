<?php


namespace App\Models\concours;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Class Candidat
 *
 * @property string $ca_code
 * @property int $id
 * @property string $filiere_code
 * @property int $code_site
 * @property string $ca_nom
 * @property string $ca_prenom
 * @property string $ca_sexe
 * @property Carbon $ca_date_naiss
 * @property string $ca_lieu_naiss
 * @property string $ca_statut_mat
 * @property string|null $ca_adresse
 * @property string $ca_telephone
 * @property string $ca_num_cni
 * @property string $ca_email
 * @property string $ca_premiere_lang
 * @property string $ca_nationalite
 * @property string $ca_region_origine
 * @property string $ca_depart_origine
 * @property string $ca_diplome_admission
 * @property Carbon $ca_annee_diplome
 * @property string $ca_serie_diplome
 * @property string $ca_mention_diplome
 * @property string $ca_etab_diplome
 * @property string $ca_pays_diplome
 * @property string $ca_centre_examen
 * @property string $ca_centre_depot
 * @property string $ca_nom_pere
 * @property string $ca_telephone_pere
 * @property string $ca_nom_mere
 * @property string $ca_telephone_mere
 * @property string $ca_handicap
 * @property string|null $ca_email_pere
 * @property string $ca_deliv_cni
 * @property string $ca_num_recu
 * @property string $ca_recu
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property SiteEtude $site_etude
 * @property Filiere $filiere
 * @property Sessionconcour $sessionconcour
 * @property Collection|Ecole[] $ecoles
 * @property Collection|Compte[] $comptes
 * @property Collection|Mail[] $mails
 *
 * @package App\Models
 */
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
		return $this->belongsTo(Filiere::class, 'filiere_code');
	}

	public function sessionconcour(): BelongsTo
	{
		return $this->belongsTo(Sessionconcour::class, 'id');
	}

	public function ecoles(): BelongsToMany
	{
		return $this->belongsToMany(Ecole::class, 'candidat_ecoles', 'ca_code', 'code_ecole')
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
