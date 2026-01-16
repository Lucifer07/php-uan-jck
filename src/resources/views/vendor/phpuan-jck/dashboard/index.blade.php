@extends('phpuan-jck::layouts.app')

@section('title', 'Dashboard - PhpuanJck')
@section('header', 'Dashboard')

@section('content')
<div class="space-y-6">
    @if($summary)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-lg p-6 border border-indigo-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-indigo-200 text-sm font-medium">Total Traces</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($summary['total_traces'] ?? 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-lg p-6 border border-blue-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200 text-sm font-medium">Total Time</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($summary['total_time'] ?? 0, 0) }}ms</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-600 to-cyan-800 rounded-lg p-6 border border-cyan-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-cyan-200 text-sm font-medium">Avg Time</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($summary['avg_time'] ?? 0, 1) }}ms</p>
                </div>
                <div class="w-12 h-12 bg-cyan-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-600 to-red-800 rounded-lg p-6 border border-red-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-200 text-sm font-medium">Total Problems</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ count($problems) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($traces->isEmpty())
    <div class="text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-800 mb-4">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-300 mb-2">No traces yet</h3>
        <p class="text-gray-500 mb-6">Start profiling by adding <code class="px-2 py-1 bg-gray-800 text-indigo-400 rounded">?__profile=true</code> to any URL</p>
        <a href="javascript:window.location.href = window.location.href + (window.location.search ? '&' : '?') + '__profile=true'" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Profile Current Page
        </a>
    </div>
    @else
    <div class="bg-gray-900 rounded-lg border border-gray-800">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Recent Traces</h2>
            <a href="{{ route('phpuan-jck.dashboard') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                Refresh
            </a>
        </div>
        <div class="divide-y divide-gray-800">
            @foreach($traces as $trace)
            <a href="{{ route('phpuan-jck.detail', $trace->id) }}" class="block hover:bg-gray-800/50 transition-colors">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-white font-medium">Trace #{{ $trace->id }}</h3>
                        <span class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($trace->created_at)->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-gray-300">{{ number_format($trace->total_time, 2) }}ms</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                            <span class="text-gray-300">{{ number_format($trace->total_memory / 1024 / 1024, 2) }}MB</span>
                        </div>
                        @if($trace->query_count > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/50 text-yellow-400 border border-yellow-700/50">
                                {{ $trace->query_count }} queries
                            </span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if(count($problems) > 0)
    <div class="bg-gray-900 rounded-lg border border-gray-800">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Top Problems</h2>
            <a href="{{ route('phpuan-jck.problems') }}" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                View All
            </a>
        </div>
        <div class="divide-y divide-gray-800">
            @foreach(array_slice($problems, 0, 5) as $problem)
            <div class="px-6 py-4">
                <div class="flex items-start gap-4">
                    <div>
                        @if($problem['severity'] == 'critical')
                            <span class="text-2xl">🔴</span>
                        @elseif($problem['severity'] == 'high')
                            <span class="text-2xl">🟠</span>
                        @else
                            <span class="text-2xl">🟡</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-white font-medium mb-1 truncate">{{ $problem['function'] ?? 'Unknown' }}</h3>
                        <p class="text-sm text-gray-400 mb-2">
                            @if(isset($problem['self_time'])){{ number_format($problem['self_time'], 2) }}ms @endif
                            @if(isset($problem['memory_delta']))({{ number_format($problem['memory_delta'] / 1024, 2) }}KB) @endif
                        </p>
                        <a href="{{ route('phpuan-jck.detail', $problem['trace_id']) }}" 
                           class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                            → View Trace #{{ $problem['trace_id'] }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
