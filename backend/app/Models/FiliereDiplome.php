<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FiliereDiplome extends Pivot
{
    use HasFactory;

    protected $table = 'filiere_diplome';

    protected $casts = [
        'code_dip' => 'int',
        'code_serie' => 'int',
    ];

    protected $fillable = [
        'filiere_code',
        'code_dip',
        'code_serie',
    ];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'filiere_code');
    }

    public function diplome(): BelongsTo
    {
        return $this->belongsTo(Diplome::class, 'code_dip');
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class, 'code_serie');
    }
}
