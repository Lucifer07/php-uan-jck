@props(['label', 'severity' => 'default'])

@switch($severity)
    @case('success')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/50 text-green-400 border border-green-700/50">
            {{ $label }}
        </span>
        @break

    @case('warning')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/50 text-yellow-400 border border-yellow-700/50">
            {{ $label }}
        </span>
        @break

    @case('error')
    @case('critical')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/50 text-red-400 border border-red-700/50">
            {{ $label }}
        </span>
        @break

    @case('info')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-900/50 text-blue-400 border border-blue-700/50">
            {{ $label }}
        </span>
        @break

    @case('high')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-900/50 text-orange-400 border border-orange-700/50">
            {{ $label }}
        </span>
        @break

    @default
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700">
            {{ $label }}
        </span>
@endswitch
