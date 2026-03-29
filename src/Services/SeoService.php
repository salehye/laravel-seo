<?php

namespace Salehye\Seo\Services;

use Salehye\Seo\Services\SchemaBuilder;

class SeoService
{
    protected SeoTagBuilder $tagBuilder;

    public function __construct()
    {
        $this->tagBuilder = new SeoTagBuilder();
    }

    /**
     * Create a new instance
     */
    public static function make(): self
    {
        return new static();
    }

    /*
    |--------------------------------------------------------------------------
    | Basic Meta Tags
    |--------------------------------------------------------------------------
    */

    /**
     * Set page title
     */
    public function title(string $title, ?string $suffix = null): self
    {
        $this->tagBuilder->title($title, $suffix);
        return $this;
    }

    /**
     * Set meta description
     */
    public function description(string $description, int $limit = 160): self
    {
        $this->tagBuilder->description($description, $limit);
        return $this;
    }

    /**
     * Set meta keywords
     */
    public function keywords(string|array $keywords): self
    {
        $this->tagBuilder->keywords($keywords);
        return $this;
    }

    /**
     * Set page type
     */
    public function type(string $type): self
    {
        $this->tagBuilder->og('type', $type);
        return $this;
    }

    /**
     * Set page image
     */
    public function image(string $url, ?string $alt = null, ?int $width = null, ?int $height = null): self
    {
        $this->tagBuilder->imageOpenGraph($url, $alt, $width, $height);
        return $this;
    }

    /**
     * Set canonical URL
     */
    public function canonical(string $url): self
    {
        $this->tagBuilder->canonical($url);
        return $this;
    }

    /**
     * Set robots meta tag
     */
    public function robots(string $robots): self
    {
        $this->tagBuilder->robots($robots);
        return $this;
    }

    /**
     * Add additional meta tag
     */
    public function addMeta(string $name, string $content, string $type = 'name'): self
    {
        $this->tagBuilder->addMeta($name, $content, $type);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Organization & Website Schemas
    |--------------------------------------------------------------------------
    */

    /**
     * Add Organization Schema
     */
    public function addOrganizationSchema(array $data = []): self
    {
        $this->tagBuilder->schema(SchemaBuilder::organization($data), 'organization');
        return $this;
    }

    /**
     * Add Website Schema
     */
    public function addWebsiteSchema(array $data = []): self
    {
        $this->tagBuilder->schema(SchemaBuilder::website($data), 'website');
        return $this;
    }

    /**
     * Add WebPage Schema
     */
    public function addWebPageSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::webPage($data), 'webpage');
        return $this;
    }

    /**
     * Add LocalBusiness Schema
     */
    public function addLocalBusinessSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::localBusiness($data), 'localBusiness');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Content Schemas
    |--------------------------------------------------------------------------
    */

    /**
     * Add Service Schema
     */
    public function addServiceSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::service($data), 'service');
        return $this;
    }

    /**
     * Add Product Schema
     */
    public function addProductSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::product($data), 'product');
        return $this;
    }

    /**
     * Add Article Schema
     */
    public function addArticleSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::article($data), 'article');
        return $this;
    }

    /**
     * Add BlogPosting Schema
     */
    public function addBlogPostingSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::blogPosting($data), 'blogPosting');
        return $this;
    }

    /**
     * Add NewsArticle Schema
     */
    public function addNewsArticleSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::newsArticle($data), 'newsArticle');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Interactive Schemas
    |--------------------------------------------------------------------------
    */

    /**
     * Add FAQ Schema
     */
    public function addFaqSchema(array $faqs): self
    {
        $this->tagBuilder->schema(SchemaBuilder::faq($faqs), 'faq');
        return $this;
    }

    /**
     * Add AggregateRating Schema
     */
    public function addAggregateRatingSchema(float $rating, int $count): self
    {
        $this->tagBuilder->schema(SchemaBuilder::aggregateRating($rating, $count), 'aggregateRating');
        return $this;
    }

    /**
     * Add Review Schema
     */
    public function addReviewSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::review($data), 'review');
        return $this;
    }

    /**
     * Add Breadcrumb Schema
     */
    public function addBreadcrumbSchema(array $items): self
    {
        $this->tagBuilder->schema(SchemaBuilder::breadcrumb($items), 'breadcrumb');
        return $this;
    }

    /**
     * Add ItemList Schema
     */
    public function addItemListSchema(array $items, string $name): self
    {
        $this->tagBuilder->schema(SchemaBuilder::itemList($items, $name), 'itemList');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Event & Location Schemas
    |--------------------------------------------------------------------------
    */

    /**
     * Add Event Schema
     */
    public function addEventSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::event($data), 'event');
        return $this;
    }

    /**
     * Add City Schema
     */
    public function addCitySchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::city($data), 'city');
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Other Schemas
    |--------------------------------------------------------------------------
    */

    /**
     * Add Person Schema
     */
    public function addPersonSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::person($data), 'person');
        return $this;
    }

    /**
     * Add Video Schema
     */
    public function addVideoSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::video($data), 'video');
        return $this;
    }

    /**
     * Add Recipe Schema
     */
    public function addRecipeSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::recipe($data), 'recipe');
        return $this;
    }

    /**
     * Add JobPosting Schema
     */
    public function addJobPostingSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::jobPosting($data), 'jobPosting');
        return $this;
    }

    /**
     * Add Course Schema
     */
    public function addCourseSchema(array $data): self
    {
        $this->tagBuilder->schema(SchemaBuilder::course($data), 'course');
        return $this;
    }

    /**
     * Add custom structured data
     */
    public function addStructuredData(array $data, ?string $key = null): self
    {
        $this->tagBuilder->schema($data, $key);
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Open Graph & Twitter
    |--------------------------------------------------------------------------
    */

    /**
     * Add Open Graph tags
     */
    public function addOpenGraph(array $data): self
    {
        foreach ($data as $property => $content) {
            $this->tagBuilder->og($property, $content);
        }
        return $this;
    }

    /**
     * Add Twitter Card tags
     */
    public function addTwitterCard(array $data): self
    {
        foreach ($data as $name => $content) {
            $this->tagBuilder->twitter($name, $content);
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Output
    |--------------------------------------------------------------------------
    */

    /**
     * Generate SEO data as array
     */
    public function generate(): array
    {
        $tags = $this->tagBuilder->build();

        return [
            'title' => $tags['title'],
            'description' => $tags['description'],
            'keywords' => $tags['keywords'],
            'robots' => $tags['robots'],
            'canonical' => $tags['links']['canonical'] ?? url()->current(),
            'alternate_languages' => $tags['links']['alternate'] ?? [],
            'og_type' => $tags['open_graph']['type'] ?? 'website',
            'site_name' => config('seo.site_name'),
            'locale' => app()->getLocale(),
            'image' => $tags['open_graph']['image'] ?? null,
            'image_alt' => $tags['open_graph']['image:alt'] ?? null,
            'twitter_card' => $tags['twitter_card']['card'] ?? 'summary_large_image',
            'twitter_handle' => $tags['twitter_card']['site'] ?? config('seo.twitter_handle'),
            'article_data' => null,
            'additional_meta' => $tags['additional_meta'] ?? [],
            'structured_data' => $tags['structured_data'] ?? [],
        ];
    }

    /**
     * Generate HTML string
     */
    public function toHtml(): string
    {
        return $this->tagBuilder->toHtml();
    }

    /**
     * Generate for Inertia.js
     */
    public function forInertia(): array
    {
        return ['seo' => $this->generate()];
    }

    /**
     * Generate for Blade views
     */
    public function forBlade(): array
    {
        return $this->generate();
    }

    /**
     * Get the underlying tag builder
     */
    public function getTagBuilder(): SeoTagBuilder
    {
        return $this->tagBuilder;
    }

    /**
     * Clear all tags
     */
    public function clear(): self
    {
        $this->tagBuilder->clear();
        return $this;
    }
}
