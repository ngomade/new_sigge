<?php


namespace App\Models;

use App\Models\concours\Candidat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filiere';
    protected $primaryKey = 'code_filiere';
    public $incrementing = false;

    protected $fillable = [
        'code_filiere',
        'code_bureau',
        'label_filiere',
        'desc_filiere'
    ];

    public function candidats(): HasMany
    {
        return $this->hasMany(Candidat::class, 'filiere_code', 'code_filiere');
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
