@extends('phpuan-jck::layouts.app')

@section('title', 'Problems - PhpuanJck')
@section('header', 'Performance Problems')

@section('content')
<div class="space-y-6">
    @if(count($problems) > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($problems as $problem)
            <div class="bg-gray-900 rounded-lg border border-gray-800 hover:border-gray-700 transition-colors">
                <div class="px-6 py-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 mt-1">
                            @if($problem['severity'] == 'critical')
                                <div class="w-10 h-10 rounded-full bg-red-900/50 border border-red-700/50 flex items-center justify-center">
                                    <span class="text-xl">🔴</span>
                                </div>
                            @elseif($problem['severity'] == 'high')
                                <div class="w-10 h-10 rounded-full bg-orange-900/50 border border-orange-700/50 flex items-center justify-center">
                                    <span class="text-xl">🟠</span>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-yellow-900/50 border border-yellow-700/50 flex items-center justify-center">
                                    <span class="text-xl">🟡</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-white font-semibold truncate">{{ $problem['function'] ?? 'Unknown' }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium @if($problem['severity'] == 'critical') bg-red-900/50 text-red-400 border border-red-700/50 @elseif($problem['severity'] == 'high') bg-orange-900/50 text-orange-400 border border-orange-700/50 @else bg-yellow-900/50 text-yellow-400 border border-yellow-700/50 @endif uppercase">
                                    {{ $problem['severity'] }}
                                </span>
                            </div>

                            <div class="flex items-center gap-6 text-sm text-gray-400 mb-3">
                                @if(isset($problem['self_time']))
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ number_format($problem['self_time'], 2) }}ms</span>
                                    </div>
                                @endif
                                @if(isset($problem['memory_delta']))
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                        </svg>
                                        <span>{{ number_format($problem['memory_delta'] / 1024, 2) }}KB</span>
                                    </div>
                                @endif
                            </div>

                            <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                                <p class="text-sm">
                                    <span class="text-yellow-400">💡 <strong>Recommendation:</strong></span>
                                    <span class="text-gray-300">{{ $problem['recommendation'] ?? 'Review this function' }}</span>
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('phpuan-jck.detail', $problem['trace_id']) }}" 
                                       class="inline-flex items-center gap-2 text-sm text-indigo-400 hover:text-indigo-300 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Trace #{{ $problem['trace_id'] }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-900/30 border border-green-700/50 mb-4">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">No problems detected! 🎉</h3>
            <p class="text-gray-500">Your application is performing well.</p>
        </div>
    @endif
</div>
@endsection
