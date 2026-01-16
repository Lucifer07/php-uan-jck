<?php

namespace PhpuanJck\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'phpuan-jck:install';

    protected $description = 'Install PhpuanJck performance profiler';

    public function handle()
    {
        $this->info('Installing PhpuanJck...');

        $this->newLine();

        $this->info('Step 1: Publishing configuration...');
        Artisan::call('vendor:publish', [
            '--provider' => 'PhpuanJck\\Providers\\PhpuanJckServiceProvider',
            '--tag' => 'phpuan-jck-config',
        ]);
        $this->info('✓ Configuration published');

        $this->newLine();

        $this->info('Step 2: Publishing migrations...');
        Artisan::call('vendor:publish', [
            '--provider' => 'PhpuanJck\\Providers\\PhpuanJckServiceProvider',
            '--tag' => 'phpuan-jck-migrations',
        ]);
        $this->info('✓ Migrations published');

        $this->newLine();

        $this->info('Step 3: Publishing assets...');
        Artisan::call('vendor:publish', [
            '--provider' => 'PhpuanJck\\Providers\\PhpuanJckServiceProvider',
            '--tag' => 'phpuan-jck-assets',
        ]);
        $this->info('✓ Assets published');

        $this->newLine();

        $this->info('Step 4: Registering middleware...');

        $this->registerMiddleware();

        $this->info('✓ Middleware registered');

        $this->newLine();

        $this->info('✅ PhpuanJck installed successfully!');

        $this->newLine();

        $this->info('Next steps:');
        $this->line('  1. Configure Xdebug in your php.ini:');
        $this->line('     [xdebug]');
        $this->line('     xdebug.mode=profile,trace');
        $this->line('     xdebug.start_with_request=no');
        $this->line('     xdebug.trace_format=1');
        $this->line('     xdebug.trace_output_dir=/tmp');
        $this->newLine();
        $this->line('  2. Restart PHP-FPM or Valet');
        $this->newLine();
        $this->line('  3. (Optional) Edit config/phpuan-jck.php for custom settings');
        $this->newLine();
        $this->line('  4. Access the dashboard: http://your-app.test/phpuan-jck');
        $this->newLine();
        $this->line('  5. Profile any request by adding ?__profile=true to the URL');
        $this->newLine();

        $this->warn('⚠️  PhpuanJck is designed for local development only.');
        $this->warn('   Set PHPUAN_JCK_ENABLED=false in production environment.');

        return 0;
    }

    private function registerMiddleware()
    {
        $middleware = "\PhpuanJck\Middleware\ProfilerMiddleware::class";

        // Try Laravel 10+ bootstrap/app.php pattern
        $bootstrapPath = base_path('bootstrap/app.php');
        if (File::exists($bootstrapPath)) {
            $content = File::get($bootstrapPath);

            if (str_contains($content, 'ProfilerMiddleware')) {
                $this->warn('Middleware already registered');
                return;
            }

            $pattern = '/(->withMiddleware\(function \(Middleware \$middleware\) \{)/s';

            if (preg_match($pattern, $content)) {
                $replacement = "$1\n        \$middleware->append({$middleware});";
                $content = preg_replace($pattern, $replacement, $content);

                File::put($bootstrapPath, $content);
                return;
            }
        }

        // Try Laravel 9 and earlier Kernel.php pattern
        $kernelPath = base_path('app/Http/Kernel.php');
        if (File::exists($kernelPath)) {
            $content = File::get($kernelPath);

            if (str_contains($content, 'ProfilerMiddleware')) {
                $this->warn('Middleware already registered');
                return;
            }

            // Look for protected $middleware array
            $pattern = '/(protected \$middleware\s*=\s*\[)/';

            if (preg_match($pattern, $content)) {
                $replacement = "$1\n        {$middleware},";
                $content = preg_replace($pattern, $replacement, $content);

                File::put($kernelPath, $content);
                return;
            }
        }

        $this->warn('Could not automatically register middleware.');
        $this->warn('Please add \PhpuanJck\Middleware\ProfilerMiddleware::class to your middleware.');
    }
}
