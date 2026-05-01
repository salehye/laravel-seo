<?php

namespace Salehye\Seo\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Salehye\Seo\Services\SeoService;

trait HasSeo
{
    /**
     * Get SEO configuration for this model
     * Override this method in your model to define SEO settings
     */
    protected function seoConfig(): array
    {
        return [
            // Basic information
            'title' => $this->seo_title ?? $this->title ?? $this->name ?? null,
            'description' => $this->seo_description ?? $this->description ?? null,
            'keywords' => $this->seo_keywords ?? null,
            'image' => $this->seo_image ?? $this->image ?? null,
            'type' => 'website',

            // Additional meta tags
            'meta' => [],

            // Schemas (can add multiple)
            'schemas' => [],

            // Breadcrumb
            'breadcrumb' => null,

            // Open Graph
            'og' => [],

            // Twitter Card
            'twitter' => [],

            // Robots settings
            'robots' => null,
        ];
    }

    /**
     * Generate complete SEO data
     */
    public function generateSeo(array $overrides = []): array
    {
        return $this->seo($overrides)->generate();
    }

    /**
     * Apply model SEO to the global singleton
     */
    public function applySeo(array $overrides = []): SeoService
    {
        $config = array_merge($this->seoConfig(), $overrides);
        $seo = seo();

        $this->populateSeoService($seo, $config);

        return $seo;
    }

    /**
     * Get a configured SEO service instance
     */
    public function seo(array $overrides = []): SeoService
    {
        $config = array_merge($this->seoConfig(), $overrides);
        $seo = new SeoService;

        $this->populateSeoService($seo, $config);

        return $seo;
    }

    /**
     * Populate SEO service with model data
     */
    protected function populateSeoService(SeoService $seo, array $config): void
    {
        $seo->title($this->formatTitle($config['title']))
            ->description(Str::limit($config['description'] ?? '', 160))
            ->keywords($config['keywords'] ?? '')
            ->type($config['type'] ?? 'website')
            ->image($this->getImageUrl($config['image']))
            ->canonical($this->getCanonicalUrl());

        $this->addMetaTags($seo, $config);
        $this->addSchemas($seo, $config);
        $this->addBreadcrumb($seo, $config);
        $this->addOpenGraph($seo, $config);
        $this->addTwitterCard($seo, $config);
        $this->addRobots($seo, $config);
    }

    /**
     * Generate SEO with caching
     */
    public function generateSeoWithCache(int $ttl = 3600): array
    {
        if (! config('seo.cache_enabled', true)) {
            return $this->generateSeo();
        }

        return Cache::remember($this->getCacheKey(), $ttl, fn () => $this->generateSeo());
    }

    /**
     * Clear SEO cache
     */
    public function clearSeoCache(): void
    {
        Cache::forget($this->getCacheKey());
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Get cache key for this model
     */
    protected function getCacheKey(): string
    {
        $prefix = config('seo.cache_prefix', 'seo_');

        return sprintf(
            '%s%s_%s',
            $prefix,
            strtolower(class_basename($this)),
            $this->getKey() ?? 'new'
        );
    }

    /**
     * Format title with site name
     */
    protected function formatTitle(?string $title): string
    {
        if (! $title) {
            return config('seo.site_name');
        }

        return Str::contains($title, '|')
            ? $title
            : "{$title} | ".config('seo.site_name');
    }

    /**
     * Get full image URL
     */
    protected function getImageUrl($image): ?string
    {
        if (! $image) {
            return null;
        }

        // If already a full URL, return as is
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        // Otherwise, prepend storage URL
        return asset('storage/'.$image);
    }

    /**
     * Get canonical URL
     */
    protected function getCanonicalUrl(): ?string
    {
        // If explicitly set
        if (isset($this->seo_canonical)) {
            return $this->seo_canonical;
        }

        // Try to get model URL
        return $this->getModelUrl() ?? url()->current();
    }

    /**
     * Get model URL
     */
    protected function getModelUrl(): ?string
    {
        // If model has a slug
        if (isset($this->slug)) {
            return url($this->slug);
        }

        // If model has a route
        if (method_exists($this, 'getRouteKeyName')) {
            $routeKey = $this->getRouteKeyName();
            if (isset($this->$routeKey)) {
                try {
                    return route($this->getRouteName(), $this->$routeKey);
                } catch (\Exception $e) {
                    return null;
                }
            }
        }

        return null;
    }

    /**
     * Get route name for this model
     */
    protected function getRouteName(): string
    {
        $plural = Str::plural(Str::kebab(class_basename($this)));

        return "{$plural}.show";
    }

    /**
     * Add meta tags to SEO service
     */
    protected function addMetaTags(SeoService $seo, array $config): void
    {
        // Add custom meta tags from config
        foreach ($config['meta'] as $name => $content) {
            if ($content) {
                $seo->addMeta($name, $content);
            }
        }

        // Add common meta tags if available
        if (isset($this->author)) {
            $seo->addMeta('author', $this->author);
        }

        if (isset($this->created_at)) {
            $seo->addMeta('published_date', $this->created_at->format('Y-m-d'));
        }

        if (isset($this->category)) {
            $seo->addMeta('category', $this->category);
        }

        if (isset($this->tags)) {
            $tags = is_array($this->tags) ? implode(',', $this->tags) : $this->tags;
            $seo->addMeta('tags', $tags);
        }
    }

    /**
     * Add schemas to SEO service
     */
    protected function addSchemas(SeoService $seo, array $config): void
    {
        $schemas = $config['schemas'];

        // If single schema (not array of arrays)
        if (isset($schemas['type'])) {
            $schemas = [$schemas];
        }

        foreach (array_filter($schemas) as $schema) {
            $type = $schema['type'] ?? 'Service';
            unset($schema['type']);

            match ($type) {
                'Organization' => $seo->addOrganizationSchema($schema),
                'Website' => $seo->addWebsiteSchema($schema),
                'WebPage' => $seo->addWebPageSchema($schema),
                'Service' => $seo->addServiceSchema($schema),
                'Product' => $seo->addProductSchema($schema),
                'Article' => $seo->addArticleSchema($schema),
                'BlogPosting' => $seo->addBlogPostingSchema($schema),
                'NewsArticle' => $seo->addNewsArticleSchema($schema),
                'Event' => $seo->addEventSchema($schema),
                'City' => $seo->addCitySchema($schema),
                'LocalBusiness' => $seo->addLocalBusinessSchema($schema),
                'FAQ' => $seo->addFaqSchema($schema['faqs'] ?? []),
                'AggregateRating' => $seo->addAggregateRatingSchema(
                    $schema['rating'] ?? 0,
                    $schema['count'] ?? 0
                ),
                'Review' => $seo->addReviewSchema($schema),
                'Person' => $seo->addPersonSchema($schema),
                'Video' => $seo->addVideoSchema($schema),
                'Recipe' => $seo->addRecipeSchema($schema),
                'JobPosting' => $seo->addJobPostingSchema($schema),
                'Course' => $seo->addCourseSchema($schema),
                default => $seo->addStructuredData($schema),
            };
        }
    }

    /**
     * Add breadcrumb to SEO service
     */
    protected function addBreadcrumb(SeoService $seo, array $config): void
    {
        $breadcrumb = $config['breadcrumb'] ?? $this->getDefaultBreadcrumb();

        if ($breadcrumb && count($breadcrumb) > 1) {
            $seo->addBreadcrumbSchema($breadcrumb);
        }
    }

    /**
     * Get default breadcrumb
     */
    protected function getDefaultBreadcrumb(): array
    {
        $breadcrumb = [['name' => 'Home', 'url' => url('/')]];

        $name = $this->title ?? $this->name ?? null;
        if ($name) {
            $breadcrumb[] = [
                'name' => $name,
                'url' => $this->getModelUrl(),
            ];
        }

        return $breadcrumb;
    }

    /**
     * Add Open Graph tags
     */
    protected function addOpenGraph(SeoService $seo, array $config): void
    {
        $og = array_merge([
            'title' => $config['title'],
            'description' => $config['description'],
            'image' => $config['image'],
            'type' => $config['type'],
        ], $config['og']);

        foreach ($og as $property => $content) {
            if ($content) {
                $seo->addMeta("og:{$property}", $content, 'property');
            }
        }

        // Add image dimensions if image exists
        if ($config['image']) {
            $seo->addMeta('og:image:width', '1200', 'property');
            $seo->addMeta('og:image:height', '630', 'property');
        }
    }

    /**
     * Add Twitter Card tags
     */
    protected function addTwitterCard(SeoService $seo, array $config): void
    {
        $twitter = array_merge([
            'card' => 'summary_large_image',
            'title' => $config['title'],
            'description' => $config['description'],
            'image' => $config['image'],
        ], $config['twitter']);

        foreach ($twitter as $name => $content) {
            if ($content) {
                $seo->addMeta("twitter:{$name}", $content, 'name');
            }
        }
    }

    /**
     * Add robots settings
     */
    protected function addRobots(SeoService $seo, array $config): void
    {
        // If explicitly set in config
        if ($config['robots']) {
            $seo->robots($config['robots']);

            return;
        }

        // Build robots from model properties
        $robots = ['index', 'follow'];

        if (isset($this->no_index) && $this->no_index) {
            $robots = array_diff($robots, ['index']);
            $robots[] = 'noindex';
        }

        if (isset($this->no_follow) && $this->no_follow) {
            $robots = array_diff($robots, ['follow']);
            $robots[] = 'nofollow';
        }

        $seo->robots(implode(', ', $robots));
    }
}
