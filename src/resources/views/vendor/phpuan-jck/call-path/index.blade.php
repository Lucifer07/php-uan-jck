@extends('phpuan-jck::layouts.app')

@section('title', "Call Path - Trace #{$trace->id}")
@section('header', "Call Path Analysis")

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gray-900 rounded-lg border border-gray-800 px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('phpuan-jck.dashboard') }}" class="text-sm text-gray-400 hover:text-gray-300">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="{{ route('phpuan-jck.detail', $trace->id) }}" class="text-sm text-gray-400 hover:text-gray-300">Trace #{{ $trace->id }}</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-sm text-gray-300">Call Path</span>
                </div>
                <h1 class="text-xl font-semibold text-white">Execution Flow</h1>
            </div>
            <a href="{{ route('phpuan-jck.detail', $trace->id) }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-300 hover:text-white bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Trace
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-lg p-6 border border-indigo-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-500/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-indigo-200 text-sm font-medium">Total Functions</p>
                    <p class="text-2xl font-bold text-white">{{ count($callPath) }}</p>
                </div>
            </div>
        </div>

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
                    <p class="text-orange-200 text-sm font-medium">Total Memory</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($trace->total_memory / 1024 / 1024, 2) }}MB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call Path -->
    <div class="bg-gray-900 rounded-lg border border-gray-800">
        <div class="px-6 py-4 border-b border-gray-800">
            <h2 class="text-lg font-semibold text-white">📊 Execution Flow</h2>
            <p class="text-sm text-gray-400 mt-1">{{ count($callPath) }} function calls</p>
        </div>
        <div class="px-6 py-4">
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-4 max-h-[600px] overflow-y-auto scrollbar-thin">
                @if(count($callPath) > 0)
                    <div class="space-y-1">
                        @foreach($callPath as $index => $call)
                            @php $margin = $call['depth'] * 24; @endphp
                            @php $isAppCode = str_contains($call['function'], 'App\\'); @endphp
                            @php $isSlow = ($call['self_time'] ?? 0) > 50; @endphp
                            <div class="py-2 px-3 rounded border-l-2 transition-colors hover:bg-gray-700/30 {{ $isAppCode ? 'bg-blue-900/10 border-blue-500' : 'bg-gray-700/20 border-gray-600' }} {{ $isSlow ? 'bg-red-900/10 border-red-500' : '' }}" 
                                 style="margin-left: {{ $margin }}px;">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-start gap-2 flex-1 min-w-0">
                                        <span class="flex-shrink-0 w-6 h-6 rounded bg-gray-700 flex items-center justify-center text-xs text-gray-400 font-mono">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <p class="font-mono text-sm text-gray-300 truncate">{{ substr($call['function'], 0, 100) }}</p>
                                            @if($isSlow)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-red-900/30 text-red-400 border border-red-700/50 mt-1">
                                                    Slow
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs font-mono flex-shrink-0">
                                        <span class="{{ $isSlow ? 'text-red-400 font-medium' : 'text-gray-400' }}">
                                            {{ number_format($call['self_time'] ?? 0, 4) }}ms
                                        </span>
                                        <span class="text-orange-400">
                                            {{ number_format(($call['memory_delta'] ?? 0) / 1024, 1) }}KB
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-400 py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>No call path data available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
