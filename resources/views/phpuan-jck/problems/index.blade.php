@extends('phpuan-jck::layouts.telescope')

@section('content')
<div class="mb-8">
    <div class="bg-gray-800 rounded-lg p-6">
        <h1 class="text-3xl font-bold text-white mb-6">⚠️ Performance Problems</h1>
        <p class="text-gray-400 mb-4">Detected issues across all traces</p>
        
        @if(count($problems) > 0)
            <div class="space-y-4">
                @foreach($problems as $problem)
                    <div class="bg-gray-700 rounded-lg p-4 border-l-4 @if(\$problem->severity == 'critical') border-red-400 @elseif(\$problem->severity == 'high') border-yellow-400 border-orange-400">
                        <div class="flex items-start gap-3">
                            <div class="mt-1">
                                @if(\$problem->severity == 'critical')
                                    <span class="text-red-400 text-3xl">🔴</span>
                                @elseif(\$problem->severity == 'high')
                                    <span class="text-yellow-400 text-3xl">🟠</span>
                                @else
                                    <span class="text-orange-400 text-3xl">🟡</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white mb-2">{{ \$problem->function ?? 'Unknown' }}</h3>
                                <p class="text-gray-400 text-sm mb-2">{{ \$problem->message }}</p>
                                <div class="bg-gray-800 rounded p-3 mt-3">
                                    <p class="text-gray-300 text-sm">
                                        💡 <strong>Recommendation:</strong> {{ \$problem->recommendation ?? 'Review this function' }}
                                    </p>
                                    <p class="text-gray-400 text-xs mt-2">
                                        <a href="{{ route('phpuan-jck.detail.show', \$problem->trace_id) }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                                            → View Trace #{{ \$problem->trace_id }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 0 0l-5 5 0 0z"></path>
                </svg>
                <p class="text-gray-400">No problems detected!</p>
            </div>
        @endif
    </div>
@endsection
