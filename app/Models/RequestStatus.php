<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStatus extends Model
{
    protected $fillable = ['name', 'code', 'is_final', 'is_active'];

    protected function casts(): array
    {
        return ['is_final' => 'boolean', 'is_active' => 'boolean'];
    }
}
