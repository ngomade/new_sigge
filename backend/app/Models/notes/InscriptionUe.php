<?php

namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;

class InscriptionUe extends Model
{
    protected $table = 'inscription_ue';

    public $incrementing = false;

    protected $casts = [
        'etat' => 'int',
    ];

    protected $fillable = [
        'code_ins',
        'code_ue',
        'etat',
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class, 'code_ins');
    }

    public function ue()
    {
        return $this->belongsTo(Ue::class, 'code_ue');
    }
}
