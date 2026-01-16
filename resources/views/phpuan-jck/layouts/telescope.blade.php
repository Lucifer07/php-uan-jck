<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PhpuanJck - Performance Profiler</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .call-tree-node {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            padding: 4px 8px;
            margin: 2px 0;
            border-left: 2px solid #374151;
        }
        .call-tree-node.app-code {
            border-left-color: #60a5fa;
            background: rgba(96, 165, 250, 0.05);
        }
        .call-tree-node.slow {
            border-left-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
        .call-tree-node.memory {
            border-left-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }
        .metric-time {
            color: #f87171;
        font-weight: 600;
        }
        .metric-memory {
            color: #fb923c;
            font-weight: 600;
        }
        .severity-critical {
            color: #ef4444;
        font-weight: 700;
        }
        .severity-high {
            color: #f97316;
            font-weight: 700;
        }
        .severity-medium {
            color: #f59e0b;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 font-sans">
    <div id="app" class="min-h-screen">
        @yield('content')
    </div>

    <script>
        window.phpuanJck = {
            currentTrace: null,
            summary: null,
            problems: [],
        };
    </script>
</body>
</html>
