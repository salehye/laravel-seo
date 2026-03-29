<?php

namespace Salehye\Seo\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:install {--force : Overwrite existing files}';

    /**
     * The console command description.
     */
    protected $description = 'Install the SEO package configuration and assets';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Installing SEO Package...');

        // Publish config
        $this->call('vendor:publish', [
            '--provider' => 'Salehye\Seo\Providers\SeoServiceProvider',
            '--tag' => 'seo-config',
            '--force' => $this->option('force'),
        ]);

        // Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'Salehye\Seo\Providers\SeoServiceProvider',
            '--tag' => 'seo-migrations',
            '--force' => $this->option('force'),
        ]);

        // Publish views
        $this->call('vendor:publish', [
            '--provider' => 'Salehye\Seo\Providers\SeoServiceProvider',
            '--tag' => 'seo-views',
            '--force' => $this->option('force'),
        ]);

        // Create .env.example entries
        $this->addEnvVariables();

        $this->info('✅ SEO Package installed successfully!');
        $this->info('📝 Next steps:');
        $this->info('   1. Update config/seo.php with your site information');
        $this->info('   2. Add the HasSeo trait to your models');
        $this->info('   3. Add the <x-seo> component to your layouts');
        $this->info('   4. Run migrations if using database storage: php artisan migrate');

        return Command::SUCCESS;
    }

    /**
     * Add environment variables to .env.example
     */
    protected function addEnvVariables(): void
    {
        $envExamplePath = base_path('.env.example');
        
        if (!file_exists($envExamplePath)) {
            return;
        }

        $envVars = <<<EOT

# SEO Configuration
SEO_SITE_NAME=My Website
SEO_SITE_URL=https://example.com
SEO_DEFAULT_TITLE=Home
SEO_DEFAULT_DESCRIPTION=Default website description
SEO_DEFAULT_IMAGE=images/default-og-image.jpg
SEO_DEFAULT_KEYWORDS=website, laravel, seo
SEO_TWITTER_HANDLE=@website
SEO_FACEBOOK_APP_ID=
SEO_INSTAGRAM_HANDLE=website
SEO_ROBOTS=index, follow
SEO_SITEMAP_ENABLED=true
SEO_SITEMAP_FREQUENCY=daily
SEO_SITEMAP_PRIORITY=0.8
SEO_CACHE_ENABLED=true
SEO_CACHE_TTL=3600
SEO_COUNTRY=US
SEO_LANGUAGE=en
SEO_PHONE=
SEO_EMAIL=info@example.com
SEO_GOOGLE_ANALYTICS_ID=
SEO_GOOGLE_TAG_MANAGER_ID=
SEO_GOOGLE_SITE_VERIFICATION=
SEO_BING_SITE_VERIFICATION=

EOT;

        $content = file_get_contents($envExamplePath);
        
        if (!str_contains($content, '# SEO Configuration')) {
            file_put_contents($envExamplePath, $envVars, FILE_APPEND);
            $this->info('📝 Added SEO variables to .env.example');
        }
    }
}
