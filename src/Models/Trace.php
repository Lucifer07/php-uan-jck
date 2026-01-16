<?php

namespace PhpuanJck\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;

class Trace extends Model
{
    protected $fillable = [
        'uuid',
        'path',
        'total_time',
        'total_memory',
        'query_count',
        'slow_queries',
        'queries',
        'request_data',
    ];

    protected $casts = [
        'request_data' => 'array',
        'slow_queries' => 'array',
        'queries' => 'array',
    ];

    public $timestamps = true;

    public function getSlowQueriesCountAttribute(): int
    {
        return count($this->slow_queries ?? []);
    }

    public function getQueriesCountAttribute(): int
    {
        return count($this->queries ?? []);
    }
}