<?php


namespace App\Models\notes;

use App\Models\concours\User;
use Illuminate\Database\Eloquent\Model;
use Thiagoprz\CompositeKey\HasCompositeKey;


class EtudiantEc extends Model
{
    use HasCompositeKey;
	protected $table = 'etudiant_ec';
	public $incrementing = false;
	public $timestamps = false;
    protected $primaryKey = ['code_ec', 'code_user'];

    protected $fillable = [
        "code_user",
        'code_ec'
    ];
	public function ec()
	{
		return $this->belongsTo(Ec::class, 'code_ec');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'code_user');
	}
}
