@extends('phpuan-jck::layouts.app')

@section('title', 'Traces - PhpuanJck')
@section('header', 'All Traces')

@section('content')
<div class="space-y-6">
    @if($traces->isEmpty())
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-800 mb-4">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">No traces found</h3>
            <p class="text-gray-500 mb-6">Start profiling by adding <code class="px-2 py-1 bg-gray-800 text-indigo-400 rounded">?__profile=true</code> to any URL</p>
        </div>
    @else
        <div class="bg-gray-900 rounded-lg border border-gray-800 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-800 border-b border-gray-700">
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-gray-400">ID</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-gray-400">Time</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-gray-400">Memory</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-gray-400">Created</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-gray-400">Queries</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($traces as $trace)
                        <tr class="transition-colors duration-150 hover:bg-gray-800/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('phpuan-jck.detail', $trace->id) }}" class="text-white font-medium hover:text-indigo-400 transition-colors">
                                    Trace #{{ $trace->id }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ number_format($trace->total_time, 2) }}ms
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ number_format($trace->total_memory / 1024 / 1024, 2) }}MB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                {{ \Carbon\Carbon::parse($trace->created_at)->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($trace->query_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/50 text-yellow-400 border border-yellow-700/50">
                                        {{ $trace->query_count }} queries
                                    </span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-400">
                        Showing {{ $traces->firstItem() ?? 0 }} to {{ $traces->lastItem() ?? 0 }} of {{ $traces->total() }} results
                    </div>
                    {{ $traces->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
