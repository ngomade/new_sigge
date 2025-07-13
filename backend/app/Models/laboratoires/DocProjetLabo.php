<?php

namespace App\Models\laboratoires;


use Illuminate\Database\Eloquent\Model;

class DocProjetLabo extends Model
{
    protected $table = 'doc_projet_labo';
    protected $primaryKey = 'id_doc';
    public $incrementing = true;
    protected $fillable = [
        'id_doc', 'code_projet', 'titre_doc', 'path'
    ];

    public function projet()
    {
        return $this->belongsTo(ProjetLabo::class, 'code_projet', 'code_projet');
    }
}
