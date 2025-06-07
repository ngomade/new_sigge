<?php


namespace App\Models;

use App\Models\concours\Candidat;
use App\Models\concours\FiliereDiplome;
use App\Models\concours\Serie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filiere';
    protected $primaryKey = 'filiere_code';
    public $incrementing = false;

    protected $fillable = [
        'filiere_code',
        'filiere_label',
        'filiere_description'
    ];

    public function candidats(): HasMany
    {
        return $this->hasMany(Candidat::class, 'filiere_code');
    }

    public function diplomes()
    {
        return $this->belongsToMany(Diplome::class, 'filiere_diplome', 'filiere_code', 'code_dip')
            ->using(FiliereDiplome::class)
            ->withPivot(['code_serie'])
            ->withTimestamps();
    }

    public function series()
    {
        return $this->belongsToMany(Serie::class, 'filiere_diplome', 'filiere_code', 'code_serie')
            ->using(FiliereDiplome::class)
            ->withPivot(['code_dip'])
            ->withTimestamps();
    }
}
