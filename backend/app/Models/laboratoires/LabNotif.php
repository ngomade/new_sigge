<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class LabNotif extends Model
{
    protected $table = 'lab_notif';
    protected $primaryKey = 'id_notif';
    public $incrementing = true;
    protected $fillable = [
        'id_notif', 'code_lab', 'id_pers_lab_expediteur', 'id_pers_lab_destinataire', 'type', 'titre', 'message', 'lu'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
    public function expediteur()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab_expediteur', 'id_pers_lab');
    }
    public function destinataire()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab_destinataire', 'id_pers_lab');
    }
}
