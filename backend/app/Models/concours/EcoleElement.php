<?php

namespace App\Models\concours;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class EcoleElement
 *
 * * @property string $code_ecole
 * @property string $code_el
 * @property string $some_other_field
 *
 * @package App\Models
 */
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
