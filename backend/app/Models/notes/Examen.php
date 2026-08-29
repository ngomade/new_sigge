<?php

namespace App\Models\notes;

use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    protected $table = 'examen';

    protected $primaryKey = 'code_examen';

    public $incrementing = false;

    protected $fillable = [
        'code_examen',
        'code_session',
        'type_evaluation',
    ];

    public function sessionExamen()
    {
        return $this->belongsTo(SessionExamen::class, 'code_session');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'code_examen');
    }
}
