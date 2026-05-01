<?php

use Salehye\Seo\Services\SeoService;

if (! function_exists('seo')) {
    /**
     * Get the SEO service instance
     */
    function seo(): SeoService
    {
        return app('seo');
    }
}

if (! function_exists('seo_title')) {
    /**
     * Get or set the SEO title
     */
    function seo_title(?string $title = null, ?string $suffix = null): string|SeoService
    {
        if ($title === null) {
            return seo()->getTagBuilder()->build()['title'] ?? config('seo.default_title');
        }

        return seo()->title($title, $suffix);
    }
}

if (! function_exists('seo_description')) {
    /**
     * Get or set the SEO description
     */
    function seo_description(?string $description = null): string|SeoService
    {
        if ($description === null) {
            return seo()->getTagBuilder()->build()['description'] ?? config('seo.default_description');
        }

        return seo()->description($description);
    }
}

if (! function_exists('seo_keywords')) {
    /**
     * Get or set the SEO keywords
     */
    function seo_keywords(string|array|null $keywords = null): string|SeoService
    {
        if ($keywords === null) {
            return seo()->getTagBuilder()->build()['keywords'] ?? config('seo.default_keywords', '');
        }

        return seo()->keywords($keywords);
    }
}

if (! function_exists('seo_image')) {
    /**
     * Get or set the SEO image
     */
    function seo_image(?string $image = null, ?string $alt = null): string|SeoService
    {
        if ($image === null) {
            return seo()->getTagBuilder()->build()['open_graph']['image'] ?? config('seo.default_image');
        }

        return seo()->image($image, $alt);
    }
}

if (! function_exists('seo_canonical')) {
    /**
     * Get or set the canonical URL
     */
    function seo_canonical(?string $url = null): string|SeoService
    {
        if ($url === null) {
            return seo()->getTagBuilder()->build()['links']['canonical'] ?? url()->current();
        }

        return seo()->canonical($url);
    }
}

if (! function_exists('seo_robots')) {
    /**
     * Get or set the robots meta tag
     */
    function seo_robots(?string $robots = null): string|SeoService
    {
        if ($robots === null) {
            return seo()->getTagBuilder()->build()['robots'] ?? 'index, follow';
        }

        return seo()->robots($robots);
    }
}

if (! function_exists('seo_schema')) {
    /**
     * Add a schema to the page
     */
    function seo_schema(array $data, ?string $key = null): SeoService
    {
        return seo()->addStructuredData($data, $key);
    }
}

if (! function_exists('seo_organization_schema')) {
    /**
     * Add organization schema
     */
    function seo_organization_schema(array $data = []): SeoService
    {
        return seo()->addOrganizationSchema($data);
    }
}

if (! function_exists('seo_website_schema')) {
    /**
     * Add website schema
     */
    function seo_website_schema(array $data = []): SeoService
    {
        return seo()->addWebsiteSchema($data);
    }
}

if (! function_exists('seo_breadcrumb_schema')) {
    /**
     * Add breadcrumb schema
     */
    function seo_breadcrumb_schema(array $items): SeoService
    {
        return seo()->addBreadcrumbSchema($items);
    }
}

if (! function_exists('seo_faq_schema')) {
    /**
     * Add FAQ schema
     */
    function seo_faq_schema(array $faqs): SeoService
    {
        return seo()->addFaqSchema($faqs);
    }
}

if (! function_exists('seo_article_schema')) {
    /**
     * Add article schema
     */
    function seo_article_schema(array $data): SeoService
    {
        return seo()->addArticleSchema($data);
    }
}

if (! function_exists('seo_product_schema')) {
    /**
     * Add product schema
     */
    function seo_product_schema(array $data): SeoService
    {
        return seo()->addProductSchema($data);
    }
}

if (! function_exists('seo_service_schema')) {
    /**
     * Add service schema
     */
    function seo_service_schema(array $data): SeoService
    {
        return seo()->addServiceSchema($data);
    }
}

if (! function_exists('seo_aggregate_rating_schema')) {
    /**
     * Add aggregate rating schema
     */
    function seo_aggregate_rating_schema(float $rating, int $count): SeoService
    {
        return seo()->addAggregateRatingSchema($rating, $count);
    }
}

if (! function_exists('seo_og')) {
    /**
     * Add Open Graph tag
     */
    function seo_og(string $property, string $content): SeoService
    {
        return seo()->addOpenGraph([$property => $content]);
    }
}

if (! function_exists('seo_twitter')) {
    /**
     * Add Twitter Card tag
     */
    function seo_twitter(string $name, string $content): SeoService
    {
        return seo()->addTwitterCard([$name => $content]);
    }
}

if (! function_exists('seo_render')) {
    /**
     * Render all SEO tags as HTML
     */
    function seo_render(): string
    {
        return seo()->toHtml();
    }
}

if (! function_exists('seo_alternate')) {
    /**
     * Add alternate language URL
     */
    function seo_alternate(string $lang, string $url): SeoService
    {
        seo()->getTagBuilder()->alternate($lang, $url);

        return seo();
    }
}

if (! function_exists('seo_sitemap_url')) {
    /**
     * Get the sitemap URL
     */
    function seo_sitemap_url(): string
    {
        return config('seo.site_url', config('app.url')).'/sitemap.xml';
    }
}

if (! function_exists('seo_robots_url')) {
    /**
     * Get the robots.txt URL
     */
    function seo_robots_url(): string
    {
        return config('seo.site_url', config('app.url')).'/robots.txt';
    }
}

if (! function_exists('seo_default_image')) {
    /**
     * Get the default SEO image URL
     */
    function seo_default_image(): string
    {
        return asset(config('seo.default_image', 'images/default-og-image.jpg'));
    }
}

if (! function_exists('seo_site_name')) {
    /**
     * Get the site name
     */
    function seo_site_name(): string
    {
        return config('seo.site_name', config('app.name'));
    }
}

if (! function_exists('seo_social_links')) {
    /**
     * Get social media links
     */
    function seo_social_links(): array
    {
        return config('seo.social_links', []);
    }
}

if (! function_exists('seo_verification_codes')) {
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

if (! function_exists('seo_analytics_ids')) {
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
