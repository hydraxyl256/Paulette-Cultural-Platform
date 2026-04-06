<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = 'jobs';
    protected $guarded = [];

    /**
     * The `jobs` table uses integer Unix timestamps, not Carbon.
     */
    public $timestamps = false;

    protected $casts = [
        'reserved_at' => 'integer',
        'available_at' => 'integer',
        'created_at' => 'integer',
    ];
}
