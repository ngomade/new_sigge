<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class LaboMedia extends Model
{
    protected $table = 'labo_media';
    protected $primaryKey = 'id_media';
    public $incrementing = true;
    protected $fillable = [
        'id_media', 'code_lab', 'type', 'url', 'description'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
}
