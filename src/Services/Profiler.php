<?php

namespace PhpuanJck\Services;

use PhpuanJck\Watchers\QueryWatcher;
use PhpuanJck\Watchers\MemoryWatcher;
use PhpuanJck\Watchers\RequestWatcher;

class Profiler
{
    private TraceParser $traceParser;
    private QueryWatcher $queryWatcher;
    private MemoryWatcher $memoryWatcher;
    private RequestWatcher $requestWatcher;

    public function __construct(TraceParser $traceParser)
    {
        $this->traceParser = $traceParser;
        $this->queryWatcher = new QueryWatcher();
        $this->memoryWatcher = new MemoryWatcher();
        $this->requestWatcher = new RequestWatcher();
    }

    public function startProfiling(string $traceFilePath): void
    {
        if (!\function_exists('xdebug_start_trace')) {
            throw new \Exception('Xdebug is not available');
        }

        $this->queryWatcher->start();
        $this->memoryWatcher->start();
        $this->requestWatcher->start();

        \xdebug_start_trace($traceFilePath);
    }

    public function stopProfiling(): void
    {
        if (\function_exists('xdebug_stop_trace')) {
            \xdebug_stop_trace();
        }

        $this->queryWatcher->stop();
        $this->memoryWatcher->stop();
        $this->requestWatcher->stop();
    }

    public function parseTrace(string $filePath): array
    {
        if (!config('phpuan-jck.cache.enabled', true)) {
            return $this->parseTraceWithoutCache($filePath);
        }

        $cacheKey = 'profiler:trace:' . md5($filePath);

        $cached = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        $hierarchy = $this->parseTraceWithoutCache($filePath);
        $ttl = config('phpuan-jck.cache.ttl', 3600);
        \Illuminate\Support\Facades\Cache::put($cacheKey, $hierarchy, $ttl);

        return $hierarchy;
    }

    private function parseTraceWithoutCache(string $filePath): array
    {
        $parsedLines = $this->traceParser->parse($filePath);
        $hierarchy = $this->traceParser->buildHierarchy($parsedLines);
        $this->traceParser->calculateMetrics($hierarchy);

        return $hierarchy;
    }

    public function clearTraceCache(string $filePath): void
    {
        $cacheKey = 'profiler:trace:' . md5($filePath);
        \Illuminate\Support\Facades\Cache::forget($cacheKey);
    }

    public function getTopSlowestMethods(array $hierarchy, int $limit = 100): array
    {
        $flattened = $this->flattenHierarchy($hierarchy);
        $flattened = $this->filterProfilerFunctions($flattened);
        $threshold = config('phpuan-jck.slow_threshold_ms', 0);
        $flattened = array_filter($flattened, fn($item) => $item['self_time'] >= $threshold);
        usort($flattened, fn($a, $b) => $b['self_time'] <=> $a['self_time']);

        return array_slice($flattened, 0, $limit);
    }

    public function getTopMemoryConsumingMethods(array $hierarchy, int $limit = 10): array
    {
        $flattened = $this->flattenHierarchy($hierarchy);
        $flattened = $this->filterProfilerFunctions($flattened);
        $threshold = config('phpuan-jck.memory_threshold_bytes', 1048576);
        $flattened = array_filter($flattened, fn($item) => $item['memory_delta'] >= $threshold);
        usort($flattened, fn($a, $b) => $b['memory_delta'] <=> $a['memory_delta']);

        return array_slice($flattened, 0, $limit);
    }

    private function filterProfilerFunctions(array $functions): array
    {
        $profilerFunctions = [
            'xdebug_start_trace',
            'xdebug_stop_trace',
            'xdebug_time_index',
            'function_exists',
            'startProfiling',
            'stopProfiling',
            'parseTrace',
            'buildHierarchy',
            'calculateMetrics',
            'getWatcherData',
        ];

        $lowerProfilerFunctions = array_map('strtolower', $profilerFunctions);

        return array_filter($functions, function ($item) use ($lowerProfilerFunctions) {
            $funcLower = strtolower($item['function']);
            foreach ($lowerProfilerFunctions as $profFunc) {
                if (str_contains($funcLower, $profFunc)) {
                    return false;
                }
            }
            return true;
        });
    }

    private function flattenHierarchy(array $hierarchy): array
    {
        $flattened = [];

        foreach ($hierarchy as $node) {
            $flattened[] = $node;
            if (!empty($node['children'])) {
                $flattened = array_merge($flattened, $this->flattenHierarchy($node['children']));
            }
        }

        return $flattened;
    }

    public function getWatcherData(): array
    {
        return [
            'queries' => $this->queryWatcher->getData(),
            'memory' => $this->memoryWatcher->getData(),
            'request' => $this->requestWatcher->getData(),
        ];
    }
}
