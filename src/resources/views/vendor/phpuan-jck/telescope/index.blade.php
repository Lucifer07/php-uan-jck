@extends('phpuan-jck::layouts.telescope')

@section('content')
<div class="mb-8">
    <div class="bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-white mb-2">🔭 PhpuanJck (Telescope Style)</h1>
                <p class="text-gray-400">Performance profiling for Laravel applications</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="stat-card">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ count($traces) }}</h3>
                    <p class="text-gray-400">Total Traces</p>
                </div>
                <div class="stat-card">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ $traces->sum('total_time') }}</h3>
                    <p class="text-gray-400">Total Time (ms)</p>
                </div>
                <div class="stat-card">
                    <h3 class="text-2xl font-bold text-white mb-1">{{ number_format($traces->avg('total_time'), 2) }}ms</h3>
                    <p class="text-gray-400">Avg Time</p>
                </div>
            </div>

            <!-- Navigation -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <a href="{{ route('phpuan-jck.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 rounded-lg p-4 text-center transition-colors">
                    <h3 class="text-lg font-bold text-white mb-2">📊 Dashboard</h3>
                    <p class="text-indigo-200 text-sm">Overview and statistics</p>
                </a>
                <a href="{{ route('phpuan-jck.traces') }}" class="bg-blue-600 hover:bg-blue-700 rounded-lg p-4 text-center transition-colors">
                    <h3 class="text-lg font-bold text-white mb-2">🔍 All Traces</h3>
                    <p class="text-blue-200 text-sm">Browse all trace data</p>
                </a>
                <a href="{{ route('phpuan-jck.problems') }}" class="bg-red-600 hover:bg-red-700 rounded-lg p-4 text-center transition-colors">
                    <h3 class="text-lg font-bold text-white mb-2">⚠️ Problems</h3>
                    <p class="text-red-200 text-sm">Detected issues and recommendations</p>
                </a>
            </div>

            <!-- Recent Traces -->
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-2xl font-bold text-white mb-4">Recent Traces</h2>
                @if($traces->isEmpty())
                    <div class="text-center text-gray-400 py-12">
                        <p>No traces found. Profile an endpoint with <code class="bg-gray-700 px-2 py-1 rounded text-indigo-400">?__profile=true</code></p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($traces as $trace)
                            <div class="trace-item">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ route('phpuan-jck.detail', $trace->id) }}" class="text-lg font-bold hover:text-indigo-400 transition-colors">
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
        </div>
    </div>
@endsection
