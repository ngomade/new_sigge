<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;

class ModelHasRole extends Model
{
    use HasCompositeKey;

    protected $table = 'model_has_roles';

    protected $primaryKey = ['role_id', 'model_type', 'model_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'role_id' => 'int',
        'model_id' => 'int',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
