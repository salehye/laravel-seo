<?php

namespace Salehye\Seo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SitemapGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:generate-sitemap {--output= : Output file path}';

    /**
     * The console command description.
     */
    protected $description = 'Generate a static sitemap.xml file';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $output = $this->option('output') ?? public_path('sitemap.xml');
        
        $this->info('🗺️ Generating sitemap...');

        $urls = [];
        
        // Add static pages from config
        $staticPages = config('seo.sitemap.static_pages', []);
        foreach ($staticPages as $path => $options) {
            $urls[] = [
                'loc' => config('seo.site_url') . ltrim($path, '/'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => $options['frequency'] ?? config('seo.sitemap.frequency', 'daily'),
                'priority' => $options['priority'] ?? config('seo.sitemap.priority', 0.8),
            ];
        }

        // Add dynamic URLs from models that use HasSeo trait
        $models = $this->getSeoModels();
        foreach ($models as $model) {
            $this->addModelUrls($model, $urls);
        }

        // Generate XML
        $xml = $this->generateXml($urls);
        
        // Save to file
        File::put($output, $xml);
        
        $this->info('✅ Sitemap generated successfully!');
        $this->info('📁 Saved to: ' . $output);
        $this->info('📊 Total URLs: ' . count($urls));

        return Command::SUCCESS;
    }

    /**
     * Get all models that use the HasSeo trait
     */
    protected function getSeoModels(): array
    {
        // This is a simplified version - in production you might want to
        // maintain a list of models in config
        return [
            // Add your models here that use the HasSeo trait
            // Example: \App\Models\Post::class,
            // Example: \App\Models\Product::class,
        ];
    }

    /**
     * Add URLs from a model
     */
    protected function addModelUrls(string $model, array &$urls): void
    {
        if (!class_exists($model)) {
            return;
        }

        try {
            $instances = $model::all();
            
            foreach ($instances as $instance) {
                if (method_exists($instance, 'getModelUrl')) {
                    $url = $instance->getModelUrl();
                    
                    if ($url) {
                        $urls[] = [
                            'loc' => $url,
                            'lastmod' => $instance->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                            'changefreq' => config('seo.sitemap.frequency', 'daily'),
                            'priority' => config('seo.sitemap.priority', 0.8),
                        ];
                    }
                }
            }
            
            $this->info("  ✓ Added URLs from {$model}");
        } catch (\Exception $e) {
            $this->warn("  ⚠️ Error loading {$model}: " . $e->getMessage());
        }
    }

    /**
     * Generate sitemap XML
     */
    protected function generateXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml"' . PHP_EOL;
        $xml .= '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' . PHP_EOL;
        $xml .= '        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($url['loc'], ENT_QUOTES, 'UTF-8') . '</loc>' . PHP_EOL;
            
            if (isset($url['lastmod'])) {
                $xml .= '        <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            }
            
            if (isset($url['changefreq'])) {
                $xml .= '        <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            }
            
            if (isset($url['priority'])) {
                $xml .= '        <priority>' . number_format($url['priority'], 1) . '</priority>' . PHP_EOL;
            }
            
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
