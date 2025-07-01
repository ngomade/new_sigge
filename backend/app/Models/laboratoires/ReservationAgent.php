<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class ReservationAgent extends Model
{
    protected $table = 'reservation_agent';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = [
        'code_equip', 'id_pers_lab', 'debut_reserv', 'fin_reserv', 'statut'
    ];

    public function equipement()
    {
        return $this->belongsTo(Equipements::class, 'code_equip', 'code_equip');
    }
    public function membre()
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
}
