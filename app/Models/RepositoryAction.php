<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RepositoryAction extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'path',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];
}
