<?php

namespace App\Models\concours;

use App\Models\Ecole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentreExamen extends Model
{
    protected $table = 'centre_examen';

    protected $primaryKey = 'centre_exam_code';

    protected $fillable = [
        'code_ecole',
        'centre_exam_label',
    ];

    public function ecole(): BelongsTo
    {
        return $this->belongsTo(Ecole::class, 'code_ecole');
    }
}
