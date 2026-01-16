@props([
    'headers',
    'items',
    'perPage' => 20,
    'searchable' => true,
    'pagination' => null,
])

<div class="overflow-hidden bg-gray-800 rounded-lg border border-gray-700">
    @if($searchable)
        <div class="px-4 py-3 border-b border-gray-700">
            <div class="relative">
                <input type="text" 
                       placeholder="Search..." 
                       class="w-full px-4 py-2 pl-10 text-sm text-gray-300 bg-gray-900 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent placeholder-gray-500">
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-900/50 border-b border-gray-700">
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left uppercase text-gray-400">
                            {{ $header['label'] ?? $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($items as $item)
                    <tr class="transition-colors duration-150 hover:bg-gray-700/50">
                        @foreach($headers as $key => $header)
                            @if(is_array($header) && isset($header['slot']))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $header['slot']($item) }}
                                </td>
                            @else
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    {{ $item[is_string($key) ? $key : $header] ?? '' }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">No data available</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pagination)
        <div class="px-6 py-4 border-t border-gray-700">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">
                    Showing {{ $pagination->firstItem() ?? 0 }} to {{ $pagination->lastItem() ?? 0 }} of {{ $pagination->total() }} results
                </div>
                {{ $pagination->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    @endif
</div>
