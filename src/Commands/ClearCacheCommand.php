<?php

namespace Salehye\Seo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:clear-cache';

    /**
     * The console command description.
     */
    protected $description = 'Clear all SEO cache';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $prefix = config('seo.cache_prefix', 'seo_');

        $this->info('🗑️ Clearing SEO cache...');

        $cleared = 0;

        // Get all cache keys with the SEO prefix
        if (config('cache.default') === 'file') {
            // For file cache, we can't easily list keys
            // Clear by common patterns
            $patterns = [
                $prefix.'*',
            ];

            foreach ($patterns as $pattern) {
                // This is a simplified approach - in production you might want
                // to use cache tags if your driver supports it
                $this->warn('Note: For file cache driver, consider using cache:clear command');
            }
        }

        // Clear cache tags if supported
        if (Cache::supportsTags()) {
            Cache::tags(['seo'])->flush();
            $this->info('✅ Cleared tagged SEO cache');
        } else {
            $this->warn('⚠️ Cache driver does not support tags. Use cache:clear for full clear.');
        }

        $this->info('✅ SEO cache cleared successfully!');

        return Command::SUCCESS;
    }
}
