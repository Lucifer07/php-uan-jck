<!DOCTYPE html>
<html lang="en" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PhpuanJck - Performance Profiler</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        margin: 0;
            padding: 0;
        background-color: #0f172a;
            color: #e2e8f0;
        }
        .trace-item {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s;
        }
        .trace-item:hover {
            background: #334155;
            border-color: #60a5fa;
        }
        .trace-item a {
            color: #60a5fa;
            text-decoration: none;
            font-weight: 600;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 0.5rem;
            padding: 1.5rem;
            color: white;
        }
        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            line-height: 1;
        }
        .stat-card p {
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
            opacity: 0.9;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-problem {
            background: #ef4444;
            color: white;
        }
        .badge-warning {
            background: #f59e0b;
            color: white;
        }
        .badge-success {
            background: #10b981;
            color: white;
        }
        .progress-bar {
            height: 0.5rem;
            background: #374151;
            border-radius: 9999px;
            overflow: hidden;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }
        .call-tree {
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            line-height: 1.6;
        }
        .call-node {
            padding: 0.25rem 0.5rem;
            border-left: 2px solid #4b5563;
            margin-left: 1rem;
        }
        .call-node.app {
            border-left-color: #60a5fa;
        }
        .call-node.slow {
            border-left-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }
        .call-node.memory {
            border-left-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }
        .metric {
            display: inline-block;
            padding: 0.125rem 0.5rem;
            background: #1e293b;
            border-radius: 0.25rem;
            font-size: 0.75rem;
        }
        .metric-time {
            color: #60a5fa;
        }
        .metric-memory {
            color: #f59e0b;
        }
    </style>
</head>
<body class="antialiased">
    @yield('content')
</body>
</html>
