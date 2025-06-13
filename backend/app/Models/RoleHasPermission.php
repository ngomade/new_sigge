<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;


class RoleHasPermission extends Model
{
    use HasCompositeKey;
    protected $table = 'role_has_permissions';
    protected $primaryKey = ['permission_id', 'role_id'];
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'permission_id' => 'int',
        'role_id' => 'int'
    ];
    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

	public function personnel(): BelongsTo
	{
		return $this->belongsTo(Personnel::class, 'code_pers');
	}

	public function role(): BelongsTo
	{
		return $this->belongsTo(Role::class, 'id_role');
	}
}
