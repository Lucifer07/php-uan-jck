<?php

namespace PhpuanJck\Services;

use Generator;
use Illuminate\Support\Facades\Log;

class TraceParser
{
    public function parse(string $filePath): Generator
    {
        if (str_ends_with($filePath, '.gz')) {
            $handle = gzopen($filePath, 'r');
        } else {
            $handle = fopen($filePath, 'r');
        }

        if (!$handle) {
            throw new \Exception("Unable to open trace file: $filePath");
        }

        $lineCount = 0;
        $parsedCount = 0;
        while (($line = str_ends_with($filePath, '.gz') ? gzgets($handle) : fgets($handle)) !== false) {
            $lineCount++;
            $parsedLine = $this->parseLine($line);
            if ($parsedLine) {
                $parsedCount++;
                if ($this->shouldInclude($parsedLine)) {
                    yield $parsedLine;
                }
            }
        }

        if ($this->isDebugMode()) {
            Log::debug("Parsed $lineCount lines from $filePath");
        }

        if (str_ends_with($filePath, '.gz')) {
            gzclose($handle);
        } else {
            fclose($handle);
        }
    }

    private function isDebugMode(): bool
    {
        return config('phpuan-jck.debug', false) && config('app.debug', false);
    }

    private function debugLog(string $message, array $context = []): void
    {
        if ($this->isDebugMode()) {
            Log::debug($message, $context);
        }
    }

    private function parseLine(string $line): ?array
    {
        $line = trim($line);
        if (empty($line) || strpos($line, 'TRACE START') === 0 || strpos($line, 'TRACE END') === 0) {
            return null;
        }

        if (preg_match('/^\s*([\d.]+)\s+(\d+)\s+->\s+(.+)$/', $line, $matches)) {
            return [
                'level' => substr_count($line, '    '), 
                'function' => $matches[3],
                'time' => (float)$matches[1],
                'memory' => (int)$matches[2],
            ];
        }

        return null;
    }

    private function shouldInclude(array $parsedLine): bool
    {
        $ignoreNamespaces = config('phpuan-jck.ignore_namespaces', []);
        $ignorePaths = config('phpuan-jck.ignore_paths', []);

        foreach ($ignoreNamespaces as $namespace) {
            if (str_contains($parsedLine['function'], $namespace)) {
                return false;
            }
        }

        foreach ($ignorePaths as $path) {
            if (str_contains($parsedLine['function'], $path)) {
                return false;
            }
        }

        return true;
    }

    public function buildHierarchy(Generator $parsedLines): array
    {
        $hierarchy = [];
        $stack = [];
        $previousMemory = 0;

        foreach ($parsedLines as $line) {
            $line['memory_delta'] = $line['memory'] - $previousMemory;
            $previousMemory = $line['memory'];
            $this->processLineIntoHierarchy($line, $hierarchy, $stack);
        }

        return $hierarchy;
    }

    private function processLineIntoHierarchy(array $line, array &$hierarchy, array &$stack): void
    {
        $level = $line['level'];

        $node = [
            'function' => $line['function'],
            'time' => $line['time'],
            'memory' => $line['memory'],
            'memory_delta' => $line['memory_delta'],
            'self_time' => 0,
            'inclusive_time' => 0,
            'inclusive_memory' => 0,
            'children' => [],
        ];

        if ($level === 0) {
            $hierarchy[] = &$node;
            $stack = [0 => &$node];
        } elseif (isset($stack[$level - 1])) {
            $parent = &$stack[$level - 1];
            $parent['children'][] = &$node;
            $stack[$level] = &$node;
            $stack = array_slice($stack, 0, $level + 1, true);
        } else {
            $hierarchy[] = &$node;
            $stack = [$level => &$node];
        }
    }

    public function calculateMetrics(array &$hierarchy): void
    {
        foreach ($hierarchy as &$node) {
            $this->calculateNodeMetrics($node);
        }
    }

    private function calculateNodeMetrics(array &$node): void
    {
        $inclusiveTime = $node['time'];
        $inclusiveMemory = $node['memory_delta'];

        $selfTime = $node['time'];

        foreach ($node['children'] as &$child) {
            $this->calculateNodeMetrics($child);
            $inclusiveTime += $child['inclusive_time'];
            $inclusiveMemory += $child['inclusive_memory'];
            $selfTime -= $child['inclusive_time'];
        }

        $node['inclusive_time'] = $inclusiveTime;
        $node['inclusive_memory'] = $inclusiveMemory;
        $node['self_time'] = $selfTime;
    }
}