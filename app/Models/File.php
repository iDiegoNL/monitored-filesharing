<?php

namespace App\Models;

use App\Traits\HasShareIdTrait;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasShareIdTrait;

    protected $fillable = [
        'name',
        'description',
        'path',
        'permitted_group_ids',
    ];

    protected $casts = [
        'permitted_group_ids' => 'array'
    ];
}
