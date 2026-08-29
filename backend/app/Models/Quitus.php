<?php

namespace App\Models;

use App\Models\notes\Inscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quitus extends Model
{
    use HasFactory;

    protected $table = 'quitus';

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'code_tranche' => 'int',
        'code_mode' => 'int',
        'date_paiement' => 'datetime',
        'statut_quitus' => 'int',
    ];

    protected $fillable = [
        'code_ins',
        'code_tranche',
        'code_mode',
        'numero_quitus',
        'date_paiement',
        'statut_quitus',
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class, 'code_ins');
    }

    public function modepaiment(): BelongsTo
    {
        return $this->belongsTo(Modepaiment::class, 'code_mode');
    }

    public function tranche(): BelongsTo
    {
        return $this->belongsTo(Tranche::class, 'code_tranche');
    }
}
