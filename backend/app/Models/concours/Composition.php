<?php


namespace App\Models\concours;


use Illuminate\Database\Eloquent\Relations\Pivot;

class Composition extends Pivot
{
	protected $table = 'composition';

    protected $fillable = [
        "code_ecole",
        "site_code"
    ];
}
