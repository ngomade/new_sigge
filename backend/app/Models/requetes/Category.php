<?php

namespace App\Models\requetes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";
    protected $primaryKey = "code_cat";
    	public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'code_cat',
        'label_cat',
        'desc_cat'
    ];

    public function requests()
    {
        return $this->hasMany(StudentRequest::class);
    }
}
