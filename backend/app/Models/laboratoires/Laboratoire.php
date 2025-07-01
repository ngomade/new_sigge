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
        'code_lab', 'label_labo', 'desc_labo', 'logo_labo', 'sigle',
        'axes_recherche', 'email_labo', 'tel_labo', 'adresse_labo'
    ];

    public function projets()
    {
        return $this->hasMany(ProjetLabo::class, 'code_lab', 'code_lab');
    }
    public function membres()
    {
        return $this->hasMany(PersLab::class, 'code_lab', 'code_lab');
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
}
