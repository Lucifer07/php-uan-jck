@extends('phpuan-jck::layouts.telescope')

@section('content')
<div class="mb-8">
    <div class="bg-gray-800 rounded-lg p-6">
        <h2 class="text-2xl font-bold text-white mb-4">📈 Trace #{{ $trace->id }} Details</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Trace Info -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Trace Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">ID:</span>
                        <span class="text-white font-mono">#{{ $trace->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">UUID:</span>
                        <span class="text-white font-mono text-sm truncate">{{ $trace->uuid }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Created:</span>
                        <span class="text-white">{{ \Carbon\Carbon::parse($trace->created_at)->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Request URL:</span>
                        <span class="text-gray-300 truncate max-w-xs">{{ $trace->request_data['request']['url'] ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Request Method:</span>
                        <span class="text-white font-mono">{{ $trace->request_data['request']['method'] ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Time:</span>
                        <span class="metric metric-time font-mono">{{ number_format($trace->total_time, 2) }}ms</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Memory:</span>
                        <span class="metric metric-memory font-mono">{{ number_format($trace->total_memory / 1024 / 1024, 2) }} MB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Query Count:</span>
                        <span class="text-white font-mono">{{ $trace->query_count ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Slow Queries:</span>
                        <span class="badge badge-warning">{{ $trace->slow_queries_count ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Problems -->
            @if(count($hotspots) > 0)
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">🐌 Slowest Functions</h3>
                <div class="space-y-2">
                    @foreach($hotspots as $index => $hotspot)
                        <div class="bg-gray-800 rounded p-3">
                            <div class="text-sm text-gray-400 mb-1">#{{ $index + 1 }}</div>
                            <div class="font-mono text-xs text-white mb-2 truncate">{{ substr($hotspot['function'], 0, 80) }}</div>
                            <div class="flex gap-4">
                                <div>
                                    <span class="text-gray-400">Self Time:</span>
                                    <span class="metric-time">{{ number_format($hotspot['self_time'], 4) }}ms</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Inclusive:</span>
                                    <span class="text-gray-300">{{ number_format($hotspot['inclusive_time'], 4) }}ms</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Memory Issues -->
            @if(count($memoryIssues) > 0)
            <div class="bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-white mb-4">💾 Memory Hogs</h3>
                <div class="space-y-2">
                    @foreach($memoryIssues as $index => $issue)
                        <div class="bg-gray-800 rounded p-3">
                            <div class="text-sm text-gray-400 mb-1">#{{ $index + 1 }}</div>
                            <div class="font-mono text-xs text-white mb-2 truncate">{{ substr($issue['function'], 0, 80) }}</div>
                            <div class="flex gap-4">
                                <div>
                                    <span class="text-gray-400">Memory:</span>
                                    <span class="metric-memory">{{ number_format($issue['memory_delta'] / 1024, 2) }} KB</span>
                                </div>
                                <div>
                                    <span class="text-gray-400">Time:</span>
                                    <span class="text-gray-300">{{ number_format($issue['self_time'], 4) }}ms</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Call Tree -->
        <div class="bg-gray-800 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-white">📊 Call Tree</h3>
                <button onclick="window.phpuanJck.toggleCallTree()" class="text-gray-400 hover:text-white px-3 py-1 rounded border border-gray-600">
                    Expand/Collapse All
                </button>
            </div>
            
            <div class="call-tree">
                @php
                    $this->printCallTree($hierarchy, 0);
                @endphp
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-gray-800 rounded-lg p-6 mb-8">
        <h3 class="text-xl font-bold text-white mb-4">⚡ Actions</h3>
        <div class="flex gap-4">
            <a href="{{ route('phpuan-jck.dashboard.index') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                ← Back to Dashboard
            </a>
            <button onclick="navigator.clipboard.writeText('{{ url()->full() }}')" class="text-gray-300 hover:text-white px-4 py-2 rounded bg-gray-700 hover:bg-gray-600 transition-colors">
                📋 Copy Trace URL
            </button>
            <a href="http://127.0.0.1:8080/api/complex?__profile=true" target="_blank" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                🔄 Re-Profile This Request
            </a>
        </div>
    </div>

    <script>
        window.phpuanJck.toggleCallTree = function() {
            const tree = document.querySelector('.call-tree');
            const nodes = tree.querySelectorAll('.call-node');
            nodes.forEach(node => {
                const icon = node.querySelector('button');
                if (icon) {
                    icon.click();
                }
            });
        };
    </script>
@endsection
