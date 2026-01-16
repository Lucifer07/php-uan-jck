<?php

namespace PhpuanJck\Watchers;

class MemoryWatcher implements WatcherInterface
{
    private array $memorySnapshots = [];
    private bool $isWatching = false;

    public function start(): void
    {
        if ($this->isWatching) {
            return;
        }

        $this->isWatching = true;
        $this->memorySnapshots = [];

        $this->takeMemorySnapshot('start');
    }

    public function stop(): void
    {
        $this->takeMemorySnapshot('end');
        $this->isWatching = false;
    }

    public function getData(): array
    {
        return $this->memorySnapshots;
    }

    public function takeMemorySnapshot(string $label): void
    {
        if (!$this->isWatching) {
            return;
        }

        $this->memorySnapshots[] = [
            'label' => $label,
            'time' => hrtime(true),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true),
        ];
    }
}