<?php
namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class UserExterne extends Model
{
    protected $table = 'user_externe';
    protected $primaryKey = 'id_user_ext';
    public $incrementing = true;
    protected $fillable = [
        'id_user_ext', 'code_lab', 'nom_user_ext', 'prenom_user_ext', 'email_user_ext',
        'tel_user_ext', 'statut', 'pwd', 'logo_url', 'date_debut', 'date_fin',
        'motivation_path', 'cv_path'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
    public function participationsProjet()
    {
        return $this->hasMany(ParticiperProjet::class, 'id_user_ext', 'id_user_ext');
    }

    public function affectations()
    {
        return $this->hasMany(LaboratoirePersLab::class, 'id_user_externe', 'id_user_ext');
    }
}
