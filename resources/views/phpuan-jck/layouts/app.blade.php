<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PhpuanJck - Performance Profiler</title>
    @vite('resources/views/vendor/phpuan-jck/app.css')
</head>
<body class="bg-gray-900 text-gray-100">
    <div id="app" class="min-h-screen">
        @yield('content')
    </div>
    <script>
        window.phpuanJck = {
            traces: {{ json_encode($traces ?? []) }},
            currentTrace: null,
        };
    </script>
    @vite('resources/views/vendor/phpuan-jck/app.js')
</body>
</html>
