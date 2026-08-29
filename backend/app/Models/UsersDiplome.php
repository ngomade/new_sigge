<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsersDiplome extends Model
{
    use HasFactory;

    protected $table = 'users_diplome';

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'code_dip' => 'int',
        'annee_dip' => 'datetime',
    ];

    protected $fillable = [
        'code_user',
        'code_dip',
        'annee_dip',
        'institution_dip',
        'mention_dip',
        'pays_dip',
    ];

    public function diplome(): BelongsTo
    {
        return $this->belongsTo(Diplome::class, 'code_dip');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Users::class, 'code_user');
    }
}
