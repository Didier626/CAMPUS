<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Resource extends Model
{
    protected $fillable = ['code', 'name', 'resource_type', 'location', 'status', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function serviceRequests(): BelongsToMany
    {
        return $this->belongsToMany(ServiceRequest::class, 'request_resources');
    }
}
