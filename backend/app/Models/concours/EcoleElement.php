<?php

namespace App\Models\concours;


use Illuminate\Database\Eloquent\Relations\Pivot;

class EcoleElement extends Pivot
{
    protected $table = 'ecole_element';
    // protected $primaryKey = 'code_el';
    // public $incrementing = false;

    protected $fillable = [
		'code_ecole',
        'code_el',
        // Add other fillable fields as needed
    ];
}
