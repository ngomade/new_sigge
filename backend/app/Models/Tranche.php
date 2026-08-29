<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tranche extends Model
{
    protected $table = 'tranche';

    protected $primaryKey = 'code_tranche';

    public $incrementing = false;

    public $timestamps = true;

    protected $casts = [
        'code_tranche' => 'int',
        'montant_tranche' => 'int',
    ];

    protected $fillable = [
        'code_tranche',
        'lable_tranche',
        'montant_tranche',
    ];

    public function quitus()
    {
        return $this->hasMany(Quitus::class, 'code_tranche');
    }
}
