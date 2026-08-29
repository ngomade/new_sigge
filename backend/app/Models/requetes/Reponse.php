<?php

namespace App\Models\requetes;

use Illuminate\Database\Eloquent\Model;

class Reponse extends Model
{
    protected $table = 'reponses';

    protected $primaryKey = 'code_res';

    public $incrementing = false;

    protected $fillable = [
        // "code_res",
        'text_reponse',
        'code_requete',
    ];

    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($model) {
            $year = date('y'); // last two digits of current year
            $prefix = 'REP'.$year;

            // Get max sequence number for current year prefix
            $maxCode = Reponse::where('code_res', 'like', $prefix.'%')
                ->max('code_res');

            if ($maxCode) {
                $lastSequence = (int) substr($maxCode, strlen($prefix));
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            // Format sequence with leading zeros to 6 digits
            $sequenceStr = str_pad($nextSequence, 6, '0', STR_PAD_LEFT);

            $model->code_res = $prefix.$sequenceStr;
        });
    }

    public function requete()
    {
        return $this->belongsTo(Requete::class, 'code_requete');
    }
}
