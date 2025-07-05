<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'axes_recherche', 'email_labo', 'tel_labo', 'adresse_labo'
    ];

    public function  admin_pers_labo()
    {
        return $this->belongsTo(PersLab::class, 'admin_pers_labo', 'id_pers_lab');
    }

    public function projets()
    {
        return $this->hasMany(ProjetLabo::class, 'code_lab', 'code_lab');
    }

    public function pages()
    {
        return $this->hasMany(LaboPage::class, 'code_lab', 'code_lab');
    }
    public function medias()
    {
        return $this->hasMany(LaboMedia::class, 'code_lab', 'code_lab');
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
}
