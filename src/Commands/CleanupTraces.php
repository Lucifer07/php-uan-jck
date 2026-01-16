<?php

namespace PhpuanJck\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use PhpuanJck\Models\Trace;
use Carbon\Carbon;

class CleanupTraces extends Command
{
    protected $signature = 'profiler:clean {--dry-run : Show what would be deleted without deleting}';
    protected $description = 'Clean up old trace files and database records';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $retentionHours = config('phpuan-jck.cleanup.retention_hours', 24);
        $enabled = config('phpuan-jck.cleanup.enabled', true);

        if (!$enabled) {
            $this->warn('Cleanup is disabled in config');
            return Command::SUCCESS;
        }

        $this->info("Cleaning traces older than {$retentionHours} hours...");

        $cutoff = Carbon::now()->subHours($retentionHours);
        $oldTraces = Trace::where('created_at', '<', $cutoff)->get();

        if ($oldTraces->isEmpty()) {
            $this->info('No old traces found');
            return Command::SUCCESS;
        }

        $this->info("Found {$oldTraces->count()} old traces");

        $deletedFiles = 0;
        $deletedRecords = 0;

        foreach ($oldTraces as $trace) {
            $path = $trace->path;

            if ($dryRun) {
                $this->line("Would delete: {$path}");
            } else {
                if (file_exists($path)) {
                    if (unlink($path)) {
                        $deletedFiles++;
                    } else {
                        $this->warn("Failed to delete file: {$path}");
                    }
                }
                \Illuminate\Support\Facades\Cache::forget('profiler:trace:' . md5($path));
                $trace->delete();
                $deletedRecords++;
            }
        }

        if ($dryRun) {
            $this->info("Dry run complete. Would delete {$oldTraces->count()} records");
        } else {
            $this->info("Deleted {$deletedFiles} trace files");
            $this->info("Deleted {$deletedRecords} database records");
        }

        return Command::SUCCESS;
    }
}
