<?php

namespace PhpuanJck\Watchers;

use Illuminate\Http\Request;

class RequestWatcher implements WatcherInterface
{
    private array $requestData = [];
    private bool $isWatching = false;
    private ?Request $request = null;

    public function start(): void
    {
        if ($this->isWatching) {
            return;
        }

        $this->isWatching = true;
        $this->requestData = [];

        $this->request = app('request');
        $this->captureRequestData();
    }

    public function stop(): void
    {
        $this->captureResponseData();
        $this->isWatching = false;
    }

    public function getData(): array
    {
        return $this->requestData;
    }

    private function captureRequestData(): void
    {
        if (!$this->request) {
            return;
        }

        $this->requestData['request'] = [
            'method' => $this->request->method(),
            'url' => $this->request->fullUrl(),
            'headers' => $this->request->headers->all(),
            'input' => $this->request->all(),
            'start_time' => hrtime(true),
        ];
    }

    private function captureResponseData(): void
    {
        $this->requestData['response'] = [
            'end_time' => hrtime(true),
            'duration' => $this->requestData['request']['end_time'] ?? hrtime(true) - $this->requestData['request']['start_time'],
        ];
    }
}