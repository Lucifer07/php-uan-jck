# PhpuanJck ( php unit analize jerk code keys) - Laravel Performance Profiler

using Xdebug traces to detect memory leaks and slow execution paths.

## Features

- 📊 Dashboard with performance statistics
- 🔍 Trace browser and detailed view
- ⚠️ Automatic problem detection (slow functions, memory hogs, high frequency calls)
- 📈 Hotspot identification
- 💾 Memory usage tracking
- 🚀 Zero overhead when disabled

## Installation

### Quick Install (Recommended)

```bash
composer require phuan-jck/phpuan-jck
php artisan phpuan-jck:install
```

That's it! The installer will:
- Publish configuration file
- Publish migrations
- Run migrations
- Register middleware automatically

### Manual Install

If you prefer manual installation:

```bash
# 1. Install package
composer require phuan-jck/phpuan-jck

# 2. Publish configuration
php artisan vendor:publish --provider="PhpuanJck\Providers\PhpuanJckServiceProvider" --tag="phpuan-jck-config"

# 3. Publish and run migrations
php artisan vendor:publish --provider="PhpuanJck\Providers\PhpuanJckServiceProvider" --tag="phpuan-jck-migrations"
php artisan migrate

# 4. Register middleware (bootstrap/app.php for Laravel 10+)
$middleware->append(\PhpuanJck\Middleware\ProfilerMiddleware::class);
```

## Configuration

### Xdebug (Required)

Xdebug must be installed and configured:

```ini
[xdebug]
xdebug.mode=profile,trace
xdebug.start_with_request=no
xdebug.trace_format=1
xdebug.trace_output_dir=/tmp
```

Restart PHP-FPM or Valet after configuration.

### Optional Config

Edit `config/phpuan-jck.php`:

```php
return [
    'enabled' => env('PHPUAN_JCK_ENABLED', true),
    'slow_threshold_ms' => 100, // Default threshold for slow functions
    'memory_threshold_bytes' => 1048576, // 1MB
    'ignore_namespaces' => [
        'Illuminate\\',
        'Composer\\',
        'Symfony\\',
        'Carbon\\',
    ],
    'ignore_paths' => [
        '/vendor/',
        '/storage/framework/',
    ],
];
```

## Usage

### Profile a Request

Add `?__profile=true` to any URL:

```bash
curl http://your-app.test/api/users?__profile=true
```

Or visit in browser:
```
http://your-app.test/api/users?__profile=true
```

### Access Dashboard

Visit `http://your-app.test/phpuan-jck` to access the profiler dashboard.

**Available Routes:**
- `/phpuan-jck` - Main Dashboard
- `/phpuan-jck/traces` - Browse all traces
- `/phpuan-jck/detail/{id}` - View trace details
- `/phpuan-jck/problems` - View detected problems
- `/phpuan-jck/call-path` - Call path analysis
- `/phpuan-jck/telescope` - Telescope-style overview

### Problem Detection

PhpuanJck automatically detects:

- **Slow Functions** - Functions taking more than 2x threshold (default: 200ms)
- **Memory Hogs** - Functions using more than 1MB of memory
- **High Frequency Calls** - Functions called more than 20 times
- **Nested Loops** - Potential O(n²) complexity issues

## Commands

```bash
# Install PhpuanJck
php artisan phpuan-jck:install

# Cleanup old traces
php artisan phpuan-jck:cleanup

# Cleanup with dry-run
php artisan phpuan-jck:cleanup --dry-run
```

## Performance

- **Zero overhead** when profiling is disabled
- **Minimal overhead** (~5-10%) when profiling is enabled
- Uses Xdebug traces for accurate call stack attribution
- Stores only trace metadata in database, trace files stored separately

## Security

⚠️ **Important**: PhpuanJck is designed for **local development only**.

Disable in production:
```bash
# In .env
PHPUAN_JCK_ENABLED=false
```

Or restrict to local environment:
```php
// In config/phpuan-jck.php
'enabled' => env('APP_ENV') === 'local',
```

## Development

### Run tests

```bash
vendor/bin/pest
```

### Clear cache

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Troubleshooting

### "Xdebug not found" error
Ensure Xdebug is installed and configured in your `php.ini`.

### Traces not appearing
1. Check `config/phpuan-jck.php` has `'enabled' => true`
2. Ensure middleware is registered
3. Verify database migrations ran successfully
4. Check storage permissions for trace files

### Dashboard shows 404
1. Clear routes cache: `php artisan route:clear`
2. Clear config cache: `php artisan config:clear`
3. Verify ServiceProvider is registered

## License

MIT License
