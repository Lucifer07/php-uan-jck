@extends('phpuan-jck::layouts.telescope')

@section('content')
<div class="mb-8">
    <div class="bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">📊 PhpuanJck Dashboard</h1>
                <p class="text-gray-400">Performance profiler for your Laravel application</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="stat-card">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ number_format($summary['total_traces'] ?? 0) }}</h3>
                    <p class="text-gray-400">Total Traces</p>
                </div>
                <div class="stat-card">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ number_format($summary['total_time'] ?? 0) }}ms</h3>
                    <p class="text-gray-400">Total Time</p>
                </div>
                <div class="stat-card">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ number_format($summary['avg_time'], 2) }}ms</h3>
                    <p class="text-gray-400">Avg Time</p>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ count($problems) }}</h3>
                    <p class="text-gray-400">Total Problems</p>
                </div>
            </div>

            <!-- Recent Traces -->
            <div class="bg-gray-800 rounded-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">📈 Recent Traces</h2>
                    <a href="{{ route('phpuan-jck.dashboard.index') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                        Refresh
                    </a>
                </div>

                @if($traces->isEmpty())
                    <div class="text-center text-gray-400 py-12">
                        <p>No traces found. Profile an endpoint with <code class="bg-gray-700 px-2 py-1 rounded text-indigo-400">?__profile=true</code></p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($traces as $trace)
                            <div class="trace-item">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('phpuan-jck.dashboard.show', $trace->id) }}" class="text-lg font-bold hover:text-indigo-400 transition-colors">
                                            Trace #{{ $trace->id }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        {{ \Carbon\Carbon::parse($trace->created_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-400">Time:</span>
                                        <span class="text-white font-mono">{{ number_format($trace->total_time, 2) }}ms</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-400">Memory:</span>
                                        <span class="text-white font-mono">{{ number_format($trace->total_memory / 1024 / 1024, 2) }}MB</span>
                                    </div>
                                    <div class="flex gap-2">
                                        @if($trace->query_count > 0)
                                            <span class="badge badge-warning">{{ $trace->query_count }} queries</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Top Problems -->
            @if(count($problems) > 0)
            <div class="bg-gray-800 rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold text-white mb-4">⚠️ Top Problems</h2>
                <div class="space-y-3">
                    @foreach($problems as $problem)
                        <div class="border-b border-gray-700 pb-3">
                            <div class="flex items-start gap-4">
                                <div>
                                    @if($problem->severity == 'critical')
                                        <span class="text-red-400 text-2xl">🔴</span>
                                    @elseif($problem->severity == 'high')
                                        <span class="text-yellow-400 text-2xl">🟠</span>
                                    @else
                                        <span class="text-orange-400 text-2xl">🟡</span>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-white font-semibold text-lg">{{ $problem->function }}</h3>
                                    <p class="text-gray-400 text-sm mb-1">{{ $problem->message }}</p>
                                    <div class="bg-gray-900 rounded p-3">
                                        <p class="text-yellow-400 text-sm">
                                            💡 <strong>Recommendation:</strong> {{ $problem->recommendation }}
                                        </p>
                                        <p class="text-gray-400 text-xs mt-1">
                                            <a href="{{ route('phpuan-jck.dashboard.show', $problem->trace_id) }}" class="text-indigo-400 hover:text-indigo-300 underline">
                                                → View Trace #{{ $problem->trace_id }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-6">
                        <a href="{{ route('phpuan-jck.problems.index') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                            View All Problems →
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
