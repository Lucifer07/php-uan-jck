@extends('phpuan-jck::layouts.app')

@section('title', "Trace #{$trace->id} - PhpuanJck")
@section('header', "Trace #{$trace->id}")

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gray-900 rounded-lg border border-gray-800 px-6 py-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
                    <span>{{ \Carbon\Carbon::parse($trace->created_at)->format('Y-m-d H:i:s') }}</span>
                    <span class="text-gray-600">•</span>
                    <span class="font-mono text-xs bg-gray-800 px-2 py-0.5 rounded">{{ $trace->uuid }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-900/50 text-blue-400 border border-blue-700/50 uppercase">
                        {{ $trace->request_data['request']['method'] ?? 'GET' }}
                    </span>
                    <span class="text-gray-300 font-mono text-sm truncate max-w-lg">
                        {{ $trace->request_data['request']['url'] ?? 'N/A' }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('phpuan-jck.call-path', ['trace_id' => $trace->id]) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Call Path
                </a>
                <button onclick="window.location.reload()" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg p-6 border border-blue-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total Time</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($trace->total_time, 2) }}ms</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-600 to-orange-800 rounded-lg p-6 border border-orange-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-orange-200 text-sm font-medium">Memory</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($trace->total_memory / 1024 / 1024, 2) }}MB</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-purple-800 rounded-lg p-6 border border-purple-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-purple-200 text-sm font-medium">Queries</p>
                    <p class="text-2xl font-bold text-white">{{ $trace->query_count ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Hotspots -->
        @if(count($hotspots) > 0)
        <div class="bg-gray-900 rounded-lg border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800">
                <h2 class="text-lg font-semibold text-white">🐌 Slowest Functions</h2>
                <p class="text-sm text-gray-400 mt-1">Top {{ count($hotspots) }} functions by self time</p>
            </div>
            <div class="divide-y divide-gray-800 max-h-96 overflow-y-auto scrollbar-thin">
                @foreach($hotspots as $index => $hotspot)
                <div class="px-6 py-4 hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-800 flex items-center justify-center text-xs font-bold text-gray-400">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-mono text-sm text-gray-300 truncate mb-2">{{ substr($hotspot['function'], 0, 100) }}</p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-red-400 font-medium">{{ number_format($hotspot['self_time'], 4) }}ms</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-400">{{ number_format($hotspot['inclusive_time'], 4) }}ms</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Memory Issues -->
        @if(count($memoryIssues) > 0)
        <div class="bg-gray-900 rounded-lg border border-gray-800">
            <div class="px-6 py-4 border-b border-gray-800">
                <h2 class="text-lg font-semibold text-white">💾 Memory Hogs</h2>
                <p class="text-sm text-gray-400 mt-1">Top {{ count($memoryIssues) }} functions by memory delta</p>
            </div>
            <div class="divide-y divide-gray-800 max-h-96 overflow-y-auto scrollbar-thin">
                @foreach($memoryIssues as $index => $issue)
                <div class="px-6 py-4 hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-gray-800 flex items-center justify-center text-xs font-bold text-gray-400">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="font-mono text-sm text-gray-300 truncate mb-2">{{ substr($issue['function'], 0, 100) }}</p>
                            <div class="flex items-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8-4"></path>
                                    </svg>
                                    <span class="text-orange-400 font-medium">{{ number_format($issue['memory_delta'] / 1024, 2) }}KB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-gray-400">{{ number_format($issue['self_time'], 4) }}ms</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Queries with Tabs -->
    @if($trace->query_count > 0)
    <div class="bg-gray-900 rounded-lg border border-gray-800">
        <div class="px-6 py-4 border-b border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-white">🔍 Database Queries</h2>
                    <p class="text-sm text-gray-400 mt-1">{{ $trace->query_count }} total queries</p>
                </div>
                <div class="flex gap-1">
                    <button onclick="showQueryTab('slow')" id="tab-slow" class="query-tab px-4 py-2 text-sm font-medium rounded-t-lg transition-colors bg-gray-800 text-white border-b-2 border-indigo-500">
                        Slow ({{ count($trace->slow_queries ?? []) }})
                    </button>
                    <button onclick="showQueryTab('all')" id="tab-all" class="query-tab px-4 py-2 text-sm font-medium rounded-t-lg transition-colors text-gray-400 hover:text-white hover:bg-gray-800">
                        All ({{ count($trace->queries ?? []) }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Slow Queries Tab -->
        <div id="content-slow" class="query-tab-content">
            @if(count($trace->slow_queries ?? []) > 0)
            <div class="divide-y divide-gray-800 max-h-96 overflow-y-auto scrollbar-thin">
                @foreach($trace->slow_queries as $index => $query)
                <div class="px-6 py-4 hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-red-900/30 border border-red-700/50 flex items-center justify-center text-xs font-bold text-red-400">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-900/50 text-red-400 border border-red-700/50">
                                    {{ number_format($query['time'], 2) }}ms
                                </span>
                                @if(isset($query['connection']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">
                                        {{ $query['connection'] }}
                                    </span>
                                @endif
                            </div>
                            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700 mb-2">
                                <pre class="text-sm text-gray-300 font-mono overflow-x-auto whitespace-pre-wrap break-all">{{ $query['sql'] }}</pre>
                            </div>
                            @if(!empty($query['bindings']))
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-medium text-gray-500 flex-shrink-0 pt-0.5">Bindings:</span>
                                <div class="flex flex-wrap gap-1 flex-1">
                                    @foreach($query['bindings'] as $binding)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-blue-900/30 text-blue-400 border border-blue-700/50">
                                        {{ is_string($binding) ? '"'.htmlspecialchars(substr($binding, 0, 30)).'"' : var_export($binding, true) }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <p class="font-medium">No slow queries detected</p>
                <p class="text-sm mt-1">All {{ $trace->query_count }} queries executed within threshold</p>
            </div>
            @endif
        </div>

        <!-- All Queries Tab -->
        <div id="content-all" class="query-tab-content hidden">
            @if(count($trace->queries ?? []) > 0)
            <div class="divide-y divide-gray-800 max-h-96 overflow-y-auto scrollbar-thin">
                @foreach($trace->queries as $index => $query)
                <div class="px-6 py-4 hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-4">
                        <span class="flex-shrink-0 w-6 h-6 rounded-full {{ ($query['time'] ?? 0) > config('phpuan-jck.slow_threshold_ms', 100) ? 'bg-red-900/30 border-red-700/50' : 'bg-gray-800 border-gray-700' }} flex items-center justify-center text-xs font-bold {{ ($query['time'] ?? 0) > config('phpuan-jck.slow_threshold_ms', 100) ? 'text-red-400' : 'text-gray-400' }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ ($query['time'] ?? 0) > config('phpuan-jck.slow_threshold_ms', 100) ? 'bg-red-900/50 text-red-400 border border-red-700/50' : 'bg-gray-800 text-gray-400 border border-gray-700' }}">
                                    {{ number_format($query['time'], 2) }}ms
                                </span>
                                @if(isset($query['connection']))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">
                                        {{ $query['connection'] }}
                                    </span>
                                @endif
                                @if(($query['time'] ?? 0) > config('phpuan-jck.slow_threshold_ms', 100))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-900/50 text-orange-400 border border-orange-700/50">
                                        Slow
                                    </span>
                                @endif
                            </div>
                            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700 mb-2">
                                <pre class="text-sm text-gray-300 font-mono overflow-x-auto whitespace-pre-wrap break-all">{{ $query['sql'] }}</pre>
                            </div>
                            @if(!empty($query['bindings']))
                            <div class="flex items-start gap-2">
                                <span class="text-xs font-medium text-gray-500 flex-shrink-0 pt-0.5">Bindings:</span>
                                <div class="flex flex-wrap gap-1 flex-1">
                                    @foreach($query['bindings'] as $binding)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono bg-blue-900/30 text-blue-400 border border-blue-700/50">
                                        {{ is_string($binding) ? '"'.htmlspecialchars(substr($binding, 0, 30)).'"' : var_export($binding, true) }}
                                    </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-12 text-center text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
                <p>No queries available</p>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="bg-gray-900 rounded-lg border border-gray-800">
        <div class="px-6 py-4 border-b border-gray-800">
            <h2 class="text-lg font-semibold text-white">🔍 Database Queries</h2>
        </div>
        <div class="p-6 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
            </svg>
            <p>No database queries were executed</p>
        </div>
    </div>
    @endif

    <!-- Hierarchy -->
    <div class="bg-gray-900 rounded-lg border border-gray-800">
        <div class="px-6 py-4 border-b border-gray-800">
            <h2 class="text-lg font-semibold text-white">📊 Call Hierarchy</h2>
            <p class="text-sm text-gray-400 mt-1">{{ count($hierarchy ?? []) }} functions in execution tree</p>
        </div>
        <div class="px-6 py-4">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-4 max-h-96 overflow-y-auto scrollbar-thin">
                @if(isset($hierarchy) && count($hierarchy) > 0)
                    @php
                        $nodeIndex = 0;
                    @endphp
                    @foreach($hierarchy as $node)
                        @php
                            $isFirst = $nodeIndex === 0;
                            $isLast = $nodeIndex === count($hierarchy) - 1;
                        @endphp
                        <div class="call-tree-node py-2 px-3 mb-2 rounded border-l-2 {{ in_array(substr($node['function'], 0, 20), ['App\\', 'Database\\', 'Illuminate\\']) ? 'bg-blue-900/10 border-blue-500' : 'bg-gray-700/50 border-gray-600' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <p class="font-mono text-sm text-gray-300 truncate">{{ substr($node['function'], 0, 80) }}</p>
                                </div>
                                <div class="flex items-center gap-4 text-xs font-mono flex-shrink-0">
                                    <span class="text-red-400">{{ number_format($node['self_time'] ?? 0, 4) }}ms</span>
                                    <span class="text-orange-400">{{ number_format(($node['memory_delta'] ?? 0) / 1024, 1) }}KB</span>
                                </div>
                            </div>
                        </div>
                        @php $nodeIndex++; @endphp
                    @endforeach
                @else
                    <div class="text-center text-gray-400 py-8">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No hierarchy data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function showQueryTab(tab) {
    document.querySelectorAll('.query-tab').forEach(el => {
        el.classList.remove('bg-gray-800', 'text-white', 'border-b-2', 'border-indigo-500');
        el.classList.add('text-gray-400', 'hover:text-white', 'hover:bg-gray-800');
    });
    document.querySelectorAll('.query-tab-content').forEach(el => el.classList.add('hidden'));
    
    if (tab === 'slow') {
        document.getElementById('content-slow').classList.remove('hidden');
        document.getElementById('tab-slow').classList.add('bg-gray-800', 'text-white', 'border-b-2', 'border-indigo-500');
        document.getElementById('tab-slow').classList.remove('text-gray-400', 'hover:text-white', 'hover:bg-gray-800');
    } else {
        document.getElementById('content-all').classList.remove('hidden');
        document.getElementById('tab-all').classList.add('bg-gray-800', 'text-white', 'border-b-2', 'border-indigo-500');
        document.getElementById('tab-all').classList.remove('text-gray-400', 'hover:text-white', 'hover:bg-gray-800');
    }
}
</script>
@endsection
