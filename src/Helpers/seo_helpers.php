<?php

use Salehye\Seo\Services\SeoService;
use Salehye\Seo\Services\SchemaBuilder;

if (!function_exists('seo')) {
    /**
     * Create a new SEO service instance
     */
    function seo(): SeoService
    {
        return SeoService::make();
    }
}

if (!function_exists('seo_title')) {
    /**
     * Get or set the SEO title
     */
    function seo_title(?string $title = null): ?string
    {
        if ($title === null) {
            return View::getSection('seo_title') ?? config('seo.default_title');
        }
        
        View::share('seo_title', $title);
        return $title;
    }
}

if (!function_exists('seo_description')) {
    /**
     * Get or set the SEO description
     */
    function seo_description(?string $description = null): ?string
    {
        if ($description === null) {
            return View::getSection('seo_description') ?? config('seo.default_description');
        }
        
        View::share('seo_description', $description);
        return $description;
    }
}

if (!function_exists('seo_keywords')) {
    /**
     * Get or set the SEO keywords
     */
    function seo_keywords(string|array|null $keywords = null): ?string
    {
        if ($keywords === null) {
            return View::getSection('seo_keywords') ?? config('seo.default_keywords');
        }
        
        $keywordsString = is_array($keywords) ? implode(', ', $keywords) : $keywords;
        View::share('seo_keywords', $keywordsString);
        return $keywordsString;
    }
}

if (!function_exists('seo_image')) {
    /**
     * Get or set the SEO image
     */
    function seo_image(?string $image = null): ?string
    {
        if ($image === null) {
            return View::getSection('seo_image') ?? config('seo.default_image');
        }
        
        View::share('seo_image', $image);
        return $image;
    }
}

if (!function_exists('seo_canonical')) {
    /**
     * Get or set the canonical URL
     */
    function seo_canonical(?string $url = null): ?string
    {
        if ($url === null) {
            return View::getSection('seo_canonical') ?? url()->current();
        }
        
        View::share('seo_canonical', $url);
        return $url;
    }
}

if (!function_exists('seo_robots')) {
    /**
     * Get or set the robots meta tag
     */
    function seo_robots(?string $robots = null): ?string
    {
        if ($robots === null) {
            return View::getSection('seo_robots') ?? config('seo.robots', 'index, follow');
        }
        
        View::share('seo_robots', $robots);
        return $robots;
    }
}

if (!function_exists('seo_schema')) {
    /**
     * Add a schema to the page
     */
    function seo_schema(array $data, ?string $key = null): void
    {
        $schemas = View::getSection('seo_schemas', []);
        
        if ($key) {
            $schemas[$key] = $data;
        } else {
            $schemas[] = $data;
        }
        
        View::share('seo_schemas', $schemas);
    }
}

if (!function_exists('seo_organization_schema')) {
    /**
     * Add organization schema
     */
    function seo_organization_schema(array $data = []): void
    {
        seo_schema(SchemaBuilder::organization($data), 'organization');
    }
}

if (!function_exists('seo_website_schema')) {
    /**
     * Add website schema
     */
    function seo_website_schema(array $data = []): void
    {
        seo_schema(SchemaBuilder::website($data), 'website');
    }
}

if (!function_exists('seo_breadcrumb_schema')) {
    /**
     * Add breadcrumb schema
     */
    function seo_breadcrumb_schema(array $items): void
    {
        seo_schema(SchemaBuilder::breadcrumb($items), 'breadcrumb');
    }
}

if (!function_exists('seo_faq_schema')) {
    /**
     * Add FAQ schema
     */
    function seo_faq_schema(array $faqs): void
    {
        seo_schema(SchemaBuilder::faq($faqs), 'faq');
    }
}

if (!function_exists('seo_article_schema')) {
    /**
     * Add article schema
     */
    function seo_article_schema(array $data): void
    {
        seo_schema(SchemaBuilder::article($data), 'article');
    }
}

if (!function_exists('seo_product_schema')) {
    /**
     * Add product schema
     */
    function seo_product_schema(array $data): void
    {
        seo_schema(SchemaBuilder::product($data), 'product');
    }
}

if (!function_exists('seo_service_schema')) {
    /**
     * Add service schema
     */
    function seo_service_schema(array $data): void
    {
        seo_schema(SchemaBuilder::service($data), 'service');
    }
}

if (!function_exists('seo_aggregate_rating_schema')) {
    /**
     * Add aggregate rating schema
     */
    function seo_aggregate_rating_schema(float $rating, int $count): void
    {
        seo_schema(SchemaBuilder::aggregateRating($rating, $count), 'aggregateRating');
    }
}

if (!function_exists('seo_og')) {
    /**
     * Add Open Graph tag
     */
    function seo_og(string $property, string $content): void
    {
        $ogTags = View::getSection('seo_og', []);
        $ogTags[$property] = $content;
        View::share('seo_og', $ogTags);
    }
}

if (!function_exists('seo_twitter')) {
    /**
     * Add Twitter Card tag
     */
    function seo_twitter(string $name, string $content): void
    {
        $twitterTags = View::getSection('seo_twitter', []);
        $twitterTags[$name] = $content;
        View::share('seo_twitter', $twitterTags);
    }
}

if (!function_exists('seo_render')) {
    /**
     * Render all SEO tags as HTML
     */
    function seo_render(): string
    {
        $seo = SeoService::make();
        
        // Set basic meta tags
        if ($title = seo_title()) {
            $seo->title($title);
        }
        
        if ($description = seo_description()) {
            $seo->description($description);
        }
        
        if ($keywords = seo_keywords()) {
            $seo->keywords($keywords);
        }
        
        if ($canonical = seo_canonical()) {
            $seo->canonical($canonical);
        }
        
        if ($robots = seo_robots()) {
            $seo->robots($robots);
        }
        
        // Add Open Graph tags
        foreach (View::getSection('seo_og', []) as $property => $content) {
            $seo->addMeta("og:{$property}", $content, 'property');
        }
        
        // Add Twitter Card tags
        foreach (View::getSection('seo_twitter', []) as $name => $content) {
            $seo->addMeta("twitter:{$name}", $content, 'name');
        }
        
        // Add schemas
        foreach (View::getSection('seo_schemas', []) as $key => $schema) {
            $seo->addStructuredData($schema, $key);
        }
        
        return $seo->toHtml();
    }
}

if (!function_exists('seo_alternate')) {
    /**
     * Add alternate language URL
     */
    function seo_alternate(string $lang, string $url): void
    {
        $alternates = View::getSection('seo_alternates', []);
        $alternates[$lang] = $url;
        View::share('seo_alternates', $alternates);
    }
}

if (!function_exists('seo_sitemap_url')) {
    /**
     * Get the sitemap URL
     */
    function seo_sitemap_url(): string
    {
        return config('seo.site_url') . '/sitemap.xml';
    }
}

if (!function_exists('seo_robots_url')) {
    /**
     * Get the robots.txt URL
     */
    function seo_robots_url(): string
    {
        return config('seo.site_url') . '/robots.txt';
    }
}

if (!function_exists('seo_default_image')) {
    /**
     * Get the default SEO image URL
     */
    function seo_default_image(): string
    {
        return asset(config('seo.default_image', 'images/default-og-image.jpg'));
    }
}

if (!function_exists('seo_site_name')) {
    /**
     * Get the site name
     */
    function seo_site_name(): string
    {
        return config('seo.site_name', config('app.name'));
    }
}

if (!function_exists('seo_social_links')) {
    /**
     * Get social media links
     */
    function seo_social_links(): array
    {
        return config('seo.social_links', []);
    }
}

if (!function_exists('seo_verification_codes')) {
    /**
     * Get site verification codes
     */
    function seo_verification_codes(): array
    {
        return [
            'google' => config('seo.google_site_verification'),
            'bing' => config('seo.bing_site_verification'),
            'facebook' => config('seo.facebook_domain_verification'),
        ];
    }
}

if (!function_exists('seo_analytics_ids')) {
    /**
     * Get analytics IDs
     */
    function seo_analytics_ids(): array
    {
        return [
            'google_analytics' => config('seo.google_analytics_id'),
            'google_tag_manager' => config('seo.google_tag_manager_id'),
        ];
    }
}
