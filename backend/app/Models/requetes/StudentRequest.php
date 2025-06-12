<?php

namespace App\Models\requetes;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_title',
        'request_description',
        'status',
        'priority',
        'category_id',
        'user_id',
        'agent_id',
        'responsable_id',
        'attachment_path',
        'submitted_at',
        'assigned_at',
        'processed_at',
        'internal_notes'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'assigned_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // public function agent()
    // {
    //     return $this->belongsTo(User::class, 'agent_id');
    // }

    // public function responsable()
    // {
    //     return $this->belongsTo(User::class, 'responsable_id');
    // }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    // Scopes pour filtrer les requêtes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('agent_id');
    }
}

