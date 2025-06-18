<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\notes;

use App\Models\Ressource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EcRessource extends Model
{
	protected $table = 'ec_ressource';
	public $incrementing = false;

	protected $casts = [
		'code_res' => 'int'
	];
    protected $fillable = [
        'code_res',
        'code_ec',
        'code_pers'
    ];

	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}

	public function ressource()
	{
		return $this->belongsTo(Ressource::class, 'code_res');
	}
}
