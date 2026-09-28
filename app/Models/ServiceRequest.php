<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code', 'requester_id', 'assigned_to_user_id', 'request_type_id', 'priority_id', 'status_id',
        'title', 'description', 'location', 'submitted_at', 'assigned_at', 'started_at', 'resolved_at',
        'closed_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime', 'assigned_at' => 'datetime', 'started_at' => 'datetime',
            'resolved_at' => 'datetime', 'closed_at' => 'datetime', 'cancelled_at' => 'datetime',
        ];
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(RequestComment::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(RequestHistory::class);
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class, 'request_resources');
    }
}
