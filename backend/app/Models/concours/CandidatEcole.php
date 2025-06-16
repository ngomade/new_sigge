<?php

namespace App\Models\concours;



use Illuminate\Database\Eloquent\Relations\Pivot;


class CandidatEcole extends Pivot
{
    protected $table = 'candidat_ecoles';
    protected $fillable = [
        'ca_code',
        'code_ecole',
    ];
}
