@props(['currentRoute' => null])

<aside x-data="{ isOpen: window.innerWidth >= 768 }" 
       x-bind:class="isOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 ease-in-out bg-gray-900 border-r border-gray-800 md:translate-x-0 md:static md:inset-auto">
    <div class="flex flex-col h-full">
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-800">
            <a href="{{ route('phpuan-jck.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('vendor/phpuan-jck/images/logo.png') }}" alt="PhpuanJck Logo" class="h-8 w-auto" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <span class="text-2xl" style="display:none;">📊</span>
                <span class="text-xl font-bold text-white tracking-tight">PhpuanJck</span>
            </a>
            <button @click="isOpen = false" class="md:hidden p-2 text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('phpuan-jck.dashboard') }}" 
               @click="window.innerWidth < 768 && (isOpen = false)"
               class="{{ $currentRoute === 'phpuan-jck.dashboard' ? 'bg-gray-800 text-white border-l-4 border-indigo-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group">
                <svg class="w-5 h-5 {{ $currentRoute === 'phpuan-jck.dashboard' ? 'text-indigo-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('phpuan-jck.traces') }}" 
               @click="window.innerWidth < 768 && (isOpen = false)"
               class="{{ $currentRoute === 'phpuan-jck.traces' ? 'bg-gray-800 text-white border-l-4 border-indigo-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group">
                <svg class="w-5 h-5 {{ $currentRoute === 'phpuan-jck.traces' ? 'text-indigo-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="font-medium">Traces</span>
            </a>

            <a href="{{ route('phpuan-jck.problems') }}" 
               @click="window.innerWidth < 768 && (isOpen = false)"
               class="{{ $currentRoute === 'phpuan-jck.problems' ? 'bg-gray-800 text-white border-l-4 border-indigo-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-150 group">
                <svg class="w-5 h-5 {{ $currentRoute === 'phpuan-jck.problems' ? 'text-indigo-400' : 'text-gray-400 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <span class="font-medium">Problems</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-800">
            <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-gray-800/50">
                <img src="{{ asset('vendor/phpuan-jck/images/logo.png') }}" alt="Profile" class="w-8 h-8 rounded-full" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                <span class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white text-sm font-bold" style="display:none;">P</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">PhpuanJck</p>
                    <p class="text-xs text-gray-400 truncate">Performance Profiler</p>
                </div>
            </div>
        </div>
    </div>
</aside>

<div x-show="isOpen" 
     @click="isOpen = false" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-gray-900/50 md:hidden"></div>
