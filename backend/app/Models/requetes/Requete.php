<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\requetes;

use App\Models\Bureau;
use App\Models\Users;
use Illuminate\Database\Eloquent\Model;

class Requete extends Model
{
    protected $table = 'requetes';

    protected $primaryKey = 'code_requete';

    public $incrementing = false;

    protected $casts = [
        'date_sousmis' => 'datetime',
        'date_asignation' => 'datetime',
        'date_traitement' => 'datetime',
    ];

    protected $fillable = [
        'titre_requete',
        'desc_requete',
        'status',
        'date_sousmis',
        'date_asignation',
        'date_traitement',
        'note_interne',
        'code_cat',
        'code_user',
        'code_bureau',
    ];

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($model) {
            $year = date('y'); // last two digits of current year
            $prefix = 'REQ'.$year;

            // Get max sequence number for current year prefix
            $maxCode = Requete::where('code_requete', 'like', $prefix.'%')
                ->max('code_requete');

            if ($maxCode) {
                $lastSequence = (int) substr($maxCode, strlen($prefix));
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            // Format sequence with leading zeros to 6 digits
            $sequenceStr = str_pad($nextSequence, 6, '0', STR_PAD_LEFT);

            $model->code_requete = $prefix.$sequenceStr;
        });
    }

    public function bureau()
    {
        return $this->belongsTo(Bureau::class, 'code_bureau');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'code_cat');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'code_user');
    }

    public function fichiers()
    {
        return $this->hasMany(FichierRequete::class, 'code_requete');
    }

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'code_requete');
    }
}
