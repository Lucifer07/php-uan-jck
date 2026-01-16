<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'PhpuanJck - Performance Profiler')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: {
                            850: '#1f2937',
                            950: '#030712',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: #1f2937;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 3px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background: #4b5563;
        }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        @include('phpuan-jck::components.sidebar', ['currentRoute' => request()->route()?->getName()])

        <div class="flex-1 flex flex-col overflow-hidden md:ml-0">
            <header class="bg-gray-900 border-b border-gray-800">
                <div class="flex items-center justify-between px-6 py-4">
                    <button x-data="{ sidebarOpen: window.innerWidth >= 768 }" @click="sidebarOpen = !sidebarOpen; $dispatch('toggle-sidebar', { open: sidebarOpen })"
                            class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex-1">
                        <h1 class="text-lg font-semibold text-white">@yield('header', 'Dashboard')</h1>
                    </div>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('phpuan-jck.dashboard') }}" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto scrollbar-thin bg-gray-950">
                <div class="p-6 lg:p-8">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        window.phpuanJck = {
            currentTrace: null,
            summary: null,
            problems: [],
        };
    </script>
</body>
</html>
