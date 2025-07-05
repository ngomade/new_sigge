<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    protected $table = 'publications';
    protected $primaryKey = 'code_publi';
    public $incrementing = true;
    protected $fillable = [
        'code_publi', 'code_lab', 'titre_publi', 'type_publi', 'date_publi', 'domaine', 'resume', 'id_pers_lab'
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(PersLab::class, 'id_pers_lab', 'id_pers_lab');
    }
    public function laboratoire(): BelongsTo
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
}
