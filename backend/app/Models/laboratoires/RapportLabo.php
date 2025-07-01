<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class RapportLabo extends Model
{
    protected $table = 'rapport_labo';
    protected $primaryKey = 'code_rl';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'code_rl', 'path_rl', 'desc_rapport', 'code_lab'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
}
