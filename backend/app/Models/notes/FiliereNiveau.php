<?php

namespace App\Models\notes;

use App\Models\Filiere;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;

class FiliereNiveau extends Model
{
    use HasCompositeKey;

    protected $table = 'filiere_niveau';

    protected $primaryKey = ['code_filiere', 'code_niveau', 'code_ins'];

    public $incrementing = false;

    protected $fillable = [
        'code_filiere',
        'code_niveau',
        'code_ins',
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class, 'code_ins');
    }

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'code_niveau');
    }

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'code_filiere', 'code_filiere');
    }
}
