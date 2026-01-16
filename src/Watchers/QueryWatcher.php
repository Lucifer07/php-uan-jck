<?php

namespace PhpuanJck\Watchers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

class QueryWatcher implements WatcherInterface
{
    private array $queries = [];
    private bool $isWatching = false;
    private ?\Closure $listenerCallback = null;
    private bool $listenerRegistered = false;

    public function start(): void
    {
        if ($this->isWatching) {
            return;
        }

        $this->isWatching = true;
        $this->queries = [];

        if (!$this->listenerRegistered) {
            $this->listenerRegistered = true;
            $this->listenerCallback = function (QueryExecuted $query) {
                if ($this->isWatching) {
                    $this->queries[] = [
                        'sql' => $query->sql,
                        'bindings' => $query->bindings,
                        'time' => $query->time,
                        'connection' => $query->connectionName,
                    ];
                }
            };
            DB::listen($this->listenerCallback);
        }
    }

    public function stop(): void
    {
        $this->isWatching = false;
    }

    public function getData(): array
    {
        return $this->queries;
    }

    public function cleanup(): void
    {
        $this->isWatching = false;
        $this->queries = [];
        $this->listenerCallback = null;
        $this->listenerRegistered = false;
    }
}