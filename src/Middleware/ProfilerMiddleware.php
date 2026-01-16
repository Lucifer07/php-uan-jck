<?php

namespace PhpuanJck\Middleware;

use Closure;
use Illuminate\Http\Request;
use PhpuanJck\Services\Profiler;

class ProfilerMiddleware
{
    private Profiler $profiler;

    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->shouldProfile($request)) {
            $traceFilePath = \storage_path(config('phpuan-jck.telemetry_dir', 'telemetry') . '/' . uniqid('trace_', true) . '.xt');
            $this->ensureTelemetryDirectoryExists();

            $this->profiler->startProfiling($traceFilePath);
        }

        $response = $next($request);

        if (isset($traceFilePath)) {
            $this->profiler->stopProfiling();

            $actualPath = $this->findTraceFile($traceFilePath);
            $watcherData = $this->profiler->getWatcherData();

            try {
                $hierarchy = $this->profiler->parseTrace($actualPath);
                $totalTime = $this->calculateTotalTime($hierarchy);
                $totalMemory = $this->calculateTotalMemory($hierarchy);
                $queryCount = count($watcherData['queries'] ?? []);
                $slowQueries = array_filter($watcherData['queries'] ?? [], fn($q) => $q['time'] > config('phpuan-jck.slow_threshold_ms', 100));

                \PhpuanJck\Models\Trace::create([
                    'uuid' => uniqid(),
                    'path' => $actualPath,
                    'total_time' => $totalTime,
                    'total_memory' => $totalMemory,
                    'query_count' => $queryCount,
                    'queries' => array_values($watcherData['queries'] ?? []),
                    'slow_queries' => array_values($slowQueries),
                    'request_data' => $watcherData['request'] ?? null,
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Profiler error: ' . $e->getMessage());
            }
        }

        return $response;
    }

    private function shouldProfile(Request $request): bool
    {
        if (!config('phpuan-jck.enabled', true)) {
            return false;
        }

        if (config('app.env') === 'production') {
            return false;
        }

        if (!$request->has('__profile') || $request->get('__profile') !== 'true') {
            return false;
        }

        if (!$this->checkRateLimit($request)) {
            \Illuminate\Support\Facades\Log::warning('Profiler rate limit exceeded for IP: ' . $request->ip());
            return false;
        }

        return true;
    }

    private function checkRateLimit(Request $request): bool
    {
        $key = 'profiler:' . $request->ip();
        $maxAttempts = 10;
        $decayMinutes = 5;

        $attempts = \Illuminate\Support\Facades\Cache::get($key, 0);

        if ($attempts >= $maxAttempts) {
            return false;
        }

        \Illuminate\Support\Facades\Cache::put($key, $attempts + 1, $decayMinutes * 60);
        return true;
    }

    private function ensureTelemetryDirectoryExists(): void
    {
        $telemetryDir = \storage_path('telemetry');
        if (!\is_dir($telemetryDir)) {
            \mkdir($telemetryDir, 0755, true);
        }
    }

    private function calculateTotalTime(array $hierarchy): float
    {
        $total = 0;
        foreach ($hierarchy as $node) {
            $total += max(0, $node['inclusive_time'] ?? 0); 
        }
        return $total;
    }

    private function calculateTotalMemory(array $hierarchy): int
    {
        $total = 0;
        foreach ($hierarchy as $node) {
            $total += max(0, $node['inclusive_memory'] ?? 0); 
        }
        return $total;
    }

    private function findTraceFile(string $basePath): string
    {
        $patterns = [$basePath, $basePath . '.gz', $basePath . '.xt.gz', $basePath . '.xt'];
        foreach ($patterns as $pattern) {
            if (file_exists($pattern)) {
                return $pattern;
            }
        }
        return $basePath . '.xt.gz';
    }
}