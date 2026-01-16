<?php

use PhpuanJck\Services\TraceParser;
use PhpuanJck\Services\Profiler;
use PhpuanJck\Models\Trace;

test('trace parser can parse valid trace line', function () {
    $parser = new TraceParser();

    $reflection = new \ReflectionClass($parser);
    $parseLineMethod = $reflection->getMethod('parseLine');
    $parseLineMethod->setAccessible(true);

    $line = "0.0010    123456-> App\\Controllers\\TestController@index()";
    $result = $parseLineMethod->invoke($parser, $line);

    expect($result)->toBeArray()
        ->and($result['function'])->toBe('App\\Controllers\\TestController@index()')
        ->and($result['time'])->toBe(0.0010)
        ->and($result['memory'])->toBe(123456);
});

test('trace parser returns null for empty lines', function () {
    $parser = new TraceParser();

    $reflection = new \ReflectionClass($parser);
    $parseLineMethod = $reflection->getMethod('parseLine');
    $parseLineMethod->setAccessible(true);

    expect($parseLineMethod->invoke($parser, ''))->toBeNull();
});

test('trace parser excludes vendor paths', function () {
    $parser = new TraceParser();

    $reflection = new \ReflectionClass($parser);
    $shouldIncludeMethod = $reflection->getMethod('shouldInclude');
    $shouldIncludeMethod->setAccessible(true);

    expect($shouldIncludeMethod->invoke($parser, ['function' => 'vendor/some/file']))->toBeFalse();
});

test('trace parser includes app paths', function () {
    $parser = new TraceParser();

    $reflection = new \ReflectionClass($parser);
    $shouldIncludeMethod = $reflection->getMethod('shouldInclude');
    $shouldIncludeMethod->setAccessible(true);

    expect($shouldIncludeMethod->invoke($parser, ['function' => 'App\\Controllers\\TestController@index()']))->toBeTrue();
});

test('trace parser builds correct hierarchy', function () {
    $parser = new TraceParser();

    $traceLines = [
        ['level' => 0, 'function' => 'root()', 'time' => 1.0, 'memory' => 1000, 'memory_delta' => 100],
        ['level' => 1, 'function' => 'child1()', 'time' => 0.5, 'memory' => 1100, 'memory_delta' => 100],
        ['level' => 1, 'function' => 'child2()', 'time' => 0.3, 'memory' => 1200, 'memory_delta' => 100],
    ];

    $generator = (function () use ($traceLines) {
        foreach ($traceLines as $line) {
            yield $line;
        }
    })();

    $hierarchy = $parser->buildHierarchy($generator);
    $parser->calculateMetrics($hierarchy);

    expect($hierarchy)->toHaveCount(1)
        ->and($hierarchy[0]['function'])->toBe('root()')
        ->and($hierarchy[0]['children'])->toHaveCount(2);
});

test('query watcher does not leak listeners', function () {
    $watcher = new \PhpuanJck\Watchers\QueryWatcher();

    $watcher->start();
    $initialQueries = $watcher->getData();

    $watcher->start();
    $secondQueries = $watcher->getData();

    expect(count($initialQueries))->toBe(count($secondQueries));

    $watcher->cleanup();
});
