<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thiagoprz\CompositeKey\HasCompositeKey;

class ModelHasPermission extends Model
{
    use HasCompositeKey;

    protected $table = 'model_has_permissions';

    protected $primaryKey = ['permission_id', 'model_type', 'model_id'];

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'permission_id' => 'int',
        'model_id' => 'int',
    ];

    protected $fillable = [
        'permission_id',
        'model_type',
        'model_id',
    ];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }
}
