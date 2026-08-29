<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Serie
 *
 * @property int $code_serie
 * @property string $label_serie
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection|FiliereDiplome[] $filiere_diplomes
 */
class Serie extends Model
{
    protected $table = 'serie';

    protected $primaryKey = 'code_serie';

    protected $fillable = [
        'label_serie',
    ];

    //	public function filiere_diplomes(): HasMany
    //	{
    //		return $this->hasMany(FiliereDiplome::class, 'code_serie');
    //	}

    public function diplomes()
    {
        return $this->belongsToMany(Diplome::class, 'filiere_diplome', 'code_serie', 'code_dip')
            ->using(FiliereDiplome::class)
            ->withPivot(['filiere_code'])
            ->withTimestamps();
    }

    public function filieres()
    {
        return $this->belongsToMany(Filiere::class, 'filiere_diplome', 'code_serie', 'filiere_code')
            ->using(FiliereDiplome::class)
            ->withPivot(['code_dip'])
            ->withTimestamps();
    }
}
