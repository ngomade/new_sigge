<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjetLabo extends Model
{
    use HasFactory;

    protected $table = 'projet_labo';

    protected $primaryKey = 'code_projet';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'code_projet',
        'theme_projet',
        'description_projet',
        'code_lab',
        'statut_projet',
        'debut_projet',
        'fin_projet',
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }

    public function participants()
    {
        return $this->hasMany(ParticiperProjet::class, 'code_projet', 'code_projet');
    }

    public function docs()
    {
        return $this->hasMany(DocProjetLabo::class, 'code_projet', 'code_projet');
    }

    /**
     * Nettoie la description du projet pour l'affichage sécurisé
     */
    public function getCleanDescAttribute()
    {
        return strip_tags($this->description_projet);
    }

    /**
     * Limite la description du projet pour l'affichage court
     */
    public function getShortDescAttribute()
    {
        return Str::limit(strip_tags($this->description_projet), 150);
    }
}
