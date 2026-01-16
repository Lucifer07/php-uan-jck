<?php

namespace PhpuanJck\Watchers;

interface WatcherInterface
{
    public function start(): void;
    public function stop(): void;
    public function getData(): array;
}