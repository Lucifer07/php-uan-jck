<?php

namespace PhpuanJck\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use PhpuanJck\Models\Trace;
use PhpuanJck\Services\Profiler;

class DashboardController
{
    private Profiler $profiler;

    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    public function dashboard(): View
    {
        $traces = Trace::orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        $summary = $traces->count() > 0 ? $this->calculateSummary($traces) : null;
        $problems = $traces->count() > 0 ? $this->collectAllProblems($traces) : [];

        return view('phpuan-jck::dashboard.index', [
            'traces' => $traces,
            'summary' => $summary,
            'problems' => $problems,
        ]);
    }

    public function traces(): View
    {
        $traces = Trace::orderBy('created_at', 'desc')->paginate(20);

        return view('phpuan-jck::traces.index', [
            'traces' => $traces,
        ]);
    }

    public function show($id): View
    {
        $trace = Trace::findOrFail($id);

        $hierarchy = $this->profiler->parseTrace($trace->path);

        $flattened = $this->profiler->getTopSlowestMethods($hierarchy, 20);
        $topMemory = $this->profiler->getTopMemoryConsumingMethods($hierarchy, 10);

        return view('phpuan-jck::dashboard.detail.show', [
            'trace' => $trace,
            'hierarchy' => $hierarchy,
            'hotspots' => $flattened,
            'memoryIssues' => $topMemory,
        ]);
    }

    public function problems(): View
    {
        $traces = Trace::orderBy('created_at', 'desc')->get();

        $allProblems = $this->collectAllProblems($traces);

        return view('phpuan-jck::problems.index', [
            'problems' => $allProblems,
        ]);
    }

    public function callPath(Request $request): View
    {
        $traceId = $request->get('trace_id');
        $trace = Trace::findOrFail($traceId);

        $hierarchy = $this->profiler->parseTrace($trace->path);

        $callPath = $this->extractCallPath($hierarchy);

        return view('phpuan-jck::call-path.index', [
            'trace' => $trace,
            'callPath' => $callPath,
        ]);
    }

    public function telescope(): View
    {
        $traces = Trace::orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('phpuan-jck::telescope.index', [
            'traces' => $traces,
        ]);
    }

    public function clearCache(Request $request): RedirectResponse
    {
        $this->profiler->clearTraceCache($request->get('path'));

        return redirect()->back()->with('status', 'Cache cleared');
    }

    private function calculateSummary($traces): array
    {
        $totalTraces = $traces->count();
        $totalTime = $traces->sum('total_time');
        $totalMemory = $traces->sum('total_memory');

        return [
            'total_traces' => $totalTraces,
            'total_time' => $totalTime,
            'total_memory' => $totalMemory,
            'avg_time' => $totalTraces > 0 ? $totalTime / $totalTraces : 0,
            'avg_memory' => $totalTraces > 0 ? $totalMemory / $totalTraces : 0,
        ];
    }

    private function collectAllProblems($traces): array
    {
        $allProblems = [];

        foreach ($traces as $trace) {
            $hierarchy = $this->profiler->parseTrace($trace->path);
            $flattened = $this->profiler->getTopSlowestMethods($hierarchy, 100);
            $topMemory = $this->profiler->getTopMemoryConsumingMethods($hierarchy, 50);

            foreach (array_slice($flattened, 0, 10) as $item) {
                $selfTime = $item['self_time'] ?? 0;
                $threshold = config('phpuan-jck.slow_threshold_ms', 100);

                if ($selfTime > $threshold * 2) {
                    $allProblems[] = [
                        'trace_id' => $trace->id,
                        'trace_uuid' => $trace->uuid,
                        'type' => 'slow_function',
                        'severity' => $selfTime > $threshold * 5 ? 'critical' : 'high',
                        'function' => $item['function'],
                        'self_time' => $selfTime,
                        'recommendation' => 'Investigate and optimize this function',
                    ];
                }
            }

            foreach (array_slice($topMemory, 0, 5) as $item) {
                $memoryDelta = $item['memory_delta'] ?? 0;
                $threshold = config('phpuan-jck.memory_threshold_bytes', 1048576);

                if ($memoryDelta > $threshold) {
                    $allProblems[] = [
                        'trace_id' => $trace->id,
                        'trace_uuid' => $trace->uuid,
                        'type' => 'memory_hog',
                        'severity' => $memoryDelta > $threshold * 2 ? 'critical' : 'high',
                        'function' => $item['function'],
                        'memory_delta' => $memoryDelta,
                        'recommendation' => 'Investigate memory allocation or possible memory leak',
                    ];
                }
            }
        }

        usort($allProblems, fn($a, $b) => $b['severity'] <=> $a['severity']);

        return array_slice($allProblems, 0, 50);
    }

    private function extractCallPath(array $hierarchy): array
    {
        $path = [];
        $ignoreNamespaces = config('phpuan-jck.ignore_namespaces', []);

        $this->extractCallPathRecursive($hierarchy, $path, $ignoreNamespaces, 0, 100);

        return $path;
    }

    private function extractCallPathRecursive(array $hierarchy, array &$path, array $ignoreNamespaces, int $depth, int $maxDepth): void
    {
        if ($depth > $maxDepth || count($path) >= $maxDepth) {
            return;
        }

        foreach ($hierarchy as $node) {
            $isIgnored = false;
            foreach ($ignoreNamespaces as $namespace) {
                if (str_contains($node['function'], $namespace)) {
                    $isIgnored = true;
                    break;
                }
            }

            if (!$isIgnored) {
                $path[] = [
                    'function' => $node['function'],
                    'self_time' => round($node['self_time'], 4),
                    'memory_delta' => $node['memory_delta'],
                    'depth' => $depth,
                ];
            }

            if (!empty($node['children'])) {
                $this->extractCallPathRecursive($node['children'], $path, $ignoreNamespaces, $depth + 1, $maxDepth);
            }
        }
    }
}
