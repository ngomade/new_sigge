<?php


namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'guard_name'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permission', 'id_perm', 'id_role')
            ->withTimestamps();
    }
}
