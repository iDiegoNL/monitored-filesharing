<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name',
        'description',
        'path',
        'permitted_group_ids',
    ];

    protected $casts = [
        'permitted_group_ids' => 'array',
    ];
}
