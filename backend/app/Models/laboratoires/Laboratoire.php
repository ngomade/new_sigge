<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Laboratoire extends Model
{
    use HasFactory;

    protected $table = 'laboratoire';

    protected $primaryKey = 'code_lab';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

    protected $fillable = [
        'code_lab', 'label_labo', 'desc_labo', 'logo_labo',
        'admin_pers_labo',
        'axes_recherche', 'email_labo', 'tel_labo', 'adresse_labo',
    ];

    public function admin_pers_labo()
    {
        return $this->belongsTo(PersLab::class, 'admin_pers_labo', 'id_pers_lab');
    }

    public function projets()
    {
        return $this->hasMany(ProjetLabo::class, 'code_lab', 'code_lab');
    }

    public function publications()
    {
        return $this->hasMany(Publication::class, 'code_lab', 'code_lab');
    }

    public function notifications()
    {
        return $this->hasMany(LabNotif::class, 'code_lab', 'code_lab');
    }

    public function equipements()
    {
        return $this->hasMany(Equipements::class, 'code_lab', 'code_lab');
    }

    public function membres()
    {
        return $this->belongsToMany(PersLab::class, 'laboratoire_pers_lab', 'code_lab', 'id_pers_lab')
            ->withPivot('id_rl', 'date_affectation', 'date_fin_affectation', 'statut')
            ->withTimestamps();
    }

    public function affectations()
    {
        return $this->hasMany(LaboratoirePersLab::class, 'code_lab', 'code_lab');
    }

    /**
     * Nettoie le HTML pour l'affichage sécurisé
     */
    public function getCleanDescAttribute()
    {
        return strip_tags($this->desc_labo);
    }

    /**
     * Nettoie les axes de recherche pour l'affichage sécurisé
     */
    public function getCleanAxesAttribute()
    {
        return strip_tags($this->axes_recherche);
    }

    /**
     * Limite la description pour l'affichage court
     */
    public function getShortDescAttribute()
    {
        return Str::limit(strip_tags($this->desc_labo), 200);
    }

    /**
     * Nettoie la description du projet pour l'affichage sécurisé
     */
    public function getCleanDescProjet()
    {
        return strip_tags($this->desc_labo);
    }

    /**
     * Nettoie les axes de recherche pour l'affichage sécurisé
     */
    public function getCleanAxesRecherche()
    {
        return strip_tags($this->axes_recherche);
    }
}
