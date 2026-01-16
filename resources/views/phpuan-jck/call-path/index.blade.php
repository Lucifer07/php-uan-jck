@extends('phpuan-jck::layouts.telescope')

@section('content')
<div class="mb-8">
    <div class="bg-gray-800 rounded-lg p-6">
        <h1 class="text-3xl font-bold text-white mb-2">📊 Call Path</h1>
        <p class="text-gray-400 mb-4">Trace #{{ $trace->id }} execution flow</p>
        
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('phpuan-jck.dashboard.show', $trace->id) }}" class="text-indigo-400 hover:text-indigo-300 font-semibold">
                ← Back to Trace Detail
            </a>
            <button onclick="window.print()" class="text-gray-300 hover:text-white px-4 py-2 rounded bg-gray-700 hover:bg-gray-600">
                🖨️ Print
            </button>
        </div>

        <div class="bg-gray-900 rounded-lg p-6 call-tree">
            @foreach($callPath as $index => $call)
                <div class="call-node app-code" style="margin-left: {{ $call['depth'] * 24 }}px;">
                    <div class="flex items-center gap-2">
                        @if($call['depth'] > 0)
                            <div class="w-2 h-2 border-l border-gray-600"></div>
                        @endif
                        <span class="text-gray-400 text-xs">line {{ $index + 1 }}</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm">
                            <span class="font-mono text-white">{{ $call['function'] }}</span>
                        </div>
                        <div class="flex gap-4 text-xs">
                            <div class="metric">
                                ⏱️ {{ number_format($call['self_time'], 4) }}ms
                            </div>
                            <div class="metric">
                                💾 {{ number_format($call['memory_delta'], 0) }}B
                            </div>
                            @if($call['depth'] > 5)
                                <span class="text-gray-600 text-xs">depth: {{ $call['depth'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
