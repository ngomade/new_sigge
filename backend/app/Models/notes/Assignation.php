<?php

namespace App\Models\notes;

use app\Models\Personnel;
use Illuminate\Database\Eloquent\Model;

class Assignation extends Model
{
    protected $table = 'assignations';

    protected $primaryKey = 'code_ass';

    protected $fillable = [
        'code_ec',
        'code_class',
        'code_pers',
    ];

    public function class()
    {
        return $this->belongsTo(Classe::class, 'code_class');
    }

    public function ec()
    {
        return $this->belongsTo(Ec::class, 'code_ec');
    }

    public function personnel()
    {
        return $this->hasMany(Personnel::class, 'code_pers');
    }
}
