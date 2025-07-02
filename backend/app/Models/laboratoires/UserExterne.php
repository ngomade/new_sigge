<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class UserExterne extends Model
{
    protected $table = 'user_externe';
    protected $primaryKey = 'id_user_ext';
    public $incrementing = true;
    protected $fillable = [
        'id_user_ext', 'code_lab', 'nom_user_ext', 'prenom_user_ext', 'email_user_ext',
        'tel_user_ext', 'statut', 'pwd', 'logo_url', 'date_debut', 'date_fin'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
    public function participationsProjet()
    {
        return $this->hasMany(ParticiperProjet::class, 'id_user_ext', 'id_user_ext');
    }
}
