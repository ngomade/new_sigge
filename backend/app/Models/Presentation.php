<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presentation extends Model
{
    protected $table = 'presentation';

    protected $primaryKey = 'code_pres';

    protected $fillable = [
        'code_bureau',
        'photo_chef',
        'message_chef',
        'cursus_ing',
        'grille_ing',
        'science_ing',
        'grille_science',
        'nom_chef',
    ];

    public function bureau(): BelongsTo
    {
        return $this->belongsTo(Bureau::class, 'code_bureau');
    }
}
