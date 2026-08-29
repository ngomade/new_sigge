<?php

namespace App\Models\notes;

use App\Models\concours\User;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    protected $table = 'classes';

    protected $primaryKey = 'code_class';

    public $incrementing = false;

    protected $fillable = [
        'code_class',
        'label_class',
        'code_user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'code_user');
    }

    public function assignations()
    {
        return $this->hasMany(Assignation::class, 'code_class');
    }

    public function niveaux()
    {
        return $this->hasMany(Niveau::class, 'code_class');
    }
}
