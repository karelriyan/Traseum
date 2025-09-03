<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize 
                            {--model=news : The model to optimize images for (news, all)} 
                            {--force : Force optimization even if already optimized}
                            {--dry-run : Show what would be optimized without actually doing it}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize images for the specified model or all models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $model = $this->option('model');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        $this->info("Starting image optimization for: {$model}");

        if ($dryRun) {
            $this->warn('DRY RUN MODE - No files will be modified');
        }

        switch ($model) {
            case 'news':
                $this->optimizeNewsImages($force, $dryRun);
                break;
            case 'all':
                $this->optimizeNewsImages($force, $dryRun);
                // Add other models here when needed
                break;
            default:
                $this->error("Unknown model: {$model}");
                return 1;
        }

        $this->info('Image optimization completed!');
        return 0;
    }

    private function optimizeNewsImages(bool $force, bool $dryRun): void
    {
        $news = News::whereNotNull('featured_image')->get();
        
        if ($news->isEmpty()) {
            $this->info('No news items with images found.');
            return;
        }

        $this->info("Found {$news->count()} news items with images");

        $progressBar = $this->output->createProgressBar($news->count());
        $progressBar->start();

        $stats = [
            'processed' => 0,
            'optimized' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        foreach ($news as $newsItem) {
            $progressBar->advance();

            if (!Storage::disk('public')->exists($newsItem->featured_image)) {
                $stats['skipped']++;
                if ($this->output->isVerbose()) {
                    $this->newLine();
                    $this->warn("Skipped: {$newsItem->title} - Image file not found");
                }
                continue;
            }

            $stats['processed']++;

            if ($dryRun) {
                if ($this->output->isVerbose()) {
                    $this->newLine();
                    $this->info("Would optimize: {$newsItem->title} - {$newsItem->featured_image}");
                }
                continue;
            }

            $result = ImageOptimizationService::optimize($newsItem->featured_image, 'public');

            if ($result) {
                $stats['optimized']++;
                if ($this->output->isVerbose()) {
                    $this->newLine();
                    $this->info("Optimized: {$newsItem->title}");
                }
            } else {
                $stats['failed']++;
                if ($this->output->isVerbose()) {
                    $this->newLine();
                    $this->error("Failed: {$newsItem->title}");
                }
            }
        }

        $progressBar->finish();
        $this->newLine();

        // Display results
        $this->table(
            ['Metric', 'Count'],
            [
                ['Processed', $stats['processed']],
                ['Optimized', $stats['optimized']],
                ['Skipped', $stats['skipped']],
                ['Failed', $stats['failed']],
            ]
        );

        if ($stats['optimized'] > 0) {
            $this->info("Successfully optimized {$stats['optimized']} images!");
        }

        if ($stats['failed'] > 0) {
            $this->warn("Failed to optimize {$stats['failed']} images. Check logs for details.");
        }
    }
}
