<?php

namespace App\Models\laboratoires;

use Illuminate\Database\Eloquent\Model;

class LaboPage extends Model
{
    protected $table = 'labo_page';
    protected $primaryKey = 'id_page';
    public $incrementing = true;
    protected $fillable = [
        'id_page', 'code_lab', 'titre', 'slug', 'contenu', 'ordre'
    ];

    public function laboratoire()
    {
        return $this->belongsTo(Laboratoire::class, 'code_lab', 'code_lab');
    }
}
