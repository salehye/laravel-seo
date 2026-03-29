# Laravel SEO Package

Professional Laravel SEO Package - Complete SEO solution for Laravel applications.

![Laravel SEO](https://img.shields.io/badge/Laravel-SEO-red?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## Features

- ✅ **Complete Meta Tags** - Title, Description, Keywords, Robots, Canonical
- ✅ **Open Graph** - Full support for Facebook/LinkedIn sharing
- ✅ **Twitter Cards** - Beautiful cards for Twitter sharing
- ✅ **Structured Data (Schema.org)** - 15+ schema types
- ✅ **Sitemap.xml** - Auto-generated sitemap
- ✅ **Robots.txt** - Dynamic robots.txt
- ✅ **Multi-language** - Full i18n support
- ✅ **Cache Support** - Built-in caching for performance
- ✅ **Blade Components** - Easy-to-use `<x-seo>` component
- ✅ **Model Trait** - Add SEO to any model with `HasSeo` trait
- ✅ **Database Storage** - Optional database storage for SEO data
- ✅ **Helper Functions** - Convenient helper functions
- ✅ **Facade** - `Seo` facade for easy access
- ✅ **Console Commands** - Install, clear cache, generate sitemap

## Installation

### 1. Install via Composer

```bash
composer require salehye/laravel-seo
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --provider="Salehye\Seo\Providers\SeoServiceProvider" --tag="seo-config"
```

### 3. Publish Migrations (Optional)

```bash
php artisan vendor:publish --provider="Salehye\Seo\Providers\SeoServiceProvider" --tag="seo-migrations"
php artisan migrate
```

### 4. Publish Views (Optional)

```bash
php artisan vendor:publish --provider="Salehye\Seo\Providers\SeoServiceProvider" --tag="seo-views"
```

### 5. Quick Install Command

```bash
php artisan seo:install
```

## Configuration

Edit `config/seo.php` to customize your SEO settings:

```php
return [
    // Site Information
    'site_name' => env('SEO_SITE_NAME', 'My Website'),
    'site_url' => env('SEO_SITE_URL', 'https://example.com'),
    'default_title' => env('SEO_DEFAULT_TITLE', 'Home'),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', 'Default description'),
    'default_image' => env('SEO_DEFAULT_IMAGE', 'images/default-og-image.jpg'),
    
    // Social Media
    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@website'),
    'facebook_app_id' => env('SEO_FACEBOOK_APP_ID'),
    
    // Sitemap
    'sitemap' => [
        'enabled' => true,
        'frequency' => 'daily',
        'priority' => 0.8,
    ],
    
    // Cache
    'cache_enabled' => true,
    'cache_ttl' => 3600,
];
```

## Usage

### Method 1: Using the HasSeo Trait (Recommended for Models)

Add the trait to your model:

```php
<?php

namespace App\Models;

use Salehye\Seo\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasSeo;
    
    protected function seoConfig(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->excerpt,
            'keywords' => $this->tags?->pluck('name')->join(', '),
            'image' => $this->featured_image,
            'type' => 'article',
            
            'schemas' => [
                [
                    'type' => 'BlogPosting',
                    'headline' => $this->title,
                    'description' => $this->excerpt,
                    'author' => $this->author->name,
                    'publishedAt' => $this->published_at->toIso8601String(),
                    'image' => $this->featured_image,
                ],
            ],
            
            'breadcrumb' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Blog', 'url' => route('blog.index')],
                ['name' => $this->title, 'url' => $this->url],
            ],
        ];
    }
}
```

In your controller:

```php
public function show(Post $post)
{
    $seo = $post->generateSeo();
    
    return view('posts.show', [
        'post' => $post,
        'seo' => $seo,
    ]);
}
```

### Method 2: Using the Seo Facade

```php
use Salehye\Seo\Facades\Seo;

public function index()
{
    $seo = Seo::title('Blog Posts')
        ->description('Read our latest blog posts')
        ->keywords(['blog', 'articles', 'news'])
        ->canonical(route('blog.index'))
        ->image(asset('images/blog-og.jpg'))
        ->addOrganizationSchema()
        ->addWebsiteSchema()
        ->addBreadcrumbSchema([
            ['name' => 'Home', 'url' => url('/')],
            ['name' => 'Blog', 'url' => route('blog.index')],
        ])
        ->generate();
    
    return view('blog.index', ['seo' => $seo]);
}
```

### Method 3: Using Helper Functions

```php
// In your controller or route
seo_title('Page Title');
seo_description('Page description');
seo_keywords(['keyword1', 'keyword2']);
seo_image('image.jpg');
seo_canonical(route('page'));

// Add schemas
seo_organization_schema();
seo_website_schema();
seo_breadcrumb_schema([...]);
seo_faq_schema([...]);
```

### Method 4: Using the Blade Component

In your layout file (`layouts/app.blade.php`):

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- SEO Component --}}
    <x-seo :seo="$seo ?? []" />
    
    @stack('head')
</head>
<body>
    @yield('content')
</body>
</html>
```

## Available Schema Types

The package supports 15+ schema types:

```php
// Organization
Seo::addOrganizationSchema([...]);

// Website
Seo::addWebsiteSchema([...]);

// WebPage
Seo::addWebPageSchema([...]);

// LocalBusiness
Seo::addLocalBusinessSchema([...]);

// Service
Seo::addServiceSchema([...]);

// Product
Seo::addProductSchema([...]);

// Article / BlogPosting / NewsArticle
Seo::addArticleSchema([...]);
Seo::addBlogPostingSchema([...]);
Seo::addNewsArticleSchema([...]);

// FAQ
Seo::addFaqSchema([
    ['question' => 'Q1', 'answer' => 'A1'],
    ['question' => 'Q2', 'answer' => 'A2'],
]);

// AggregateRating
Seo::addAggregateRatingSchema(4.5, 100); // rating, count

// Review
Seo::addReviewSchema([...]);

// Breadcrumb
Seo::addBreadcrumbSchema([
    ['name' => 'Home', 'url' => url('/')],
    ['name' => 'Category', 'url' => route('category')],
    ['name' => 'Page', 'url' => url()->current()],
]);

// Event
Seo::addEventSchema([...]);

// City
Seo::addCitySchema([...]);

// Person
Seo::addPersonSchema([...]);

// Video
Seo::addVideoSchema([...]);

// Recipe
Seo::addRecipeSchema([...]);

// JobPosting
Seo::addJobPostingSchema([...]);

// Course
Seo::addCourseSchema([...]);
```

## Console Commands

### Install Package

```bash
php artisan seo:install
```

### Clear SEO Cache

```bash
php artisan seo:clear-cache
```

### Generate Static Sitemap

```bash
php artisan seo:generate-sitemap --output=public/sitemap.xml
```

## Routes

The package automatically registers these routes:

- `GET /sitemap.xml` - XML sitemap
- `GET /robots.txt` - Robots.txt file
- `GET /sitemap.xsl` - XSL stylesheet for pretty sitemap viewing

## Helper Functions

```php
// Get/Set SEO values
seo_title('Title')
seo_description('Description')
seo_keywords(['key1', 'key2'])
seo_image('image.jpg')
seo_canonical('https://example.com/page')
seo_robots('noindex, nofollow')

// Add schemas
seo_schema([...])
seo_organization_schema()
seo_website_schema()
seo_breadcrumb_schema([...])
seo_faq_schema([...])
seo_article_schema([...])
seo_product_schema([...])
seo_aggregate_rating_schema(4.5, 100)

// Open Graph & Twitter
seo_og('title', 'Title')
seo_twitter('card', 'summary_large_image')

// Render all SEO tags
seo_render()

// Utility
seo_default_image()
seo_site_name()
seo_social_links()
seo_verification_codes()
seo_analytics_ids()
seo_sitemap_url()
seo_robots_url()
```

## Database Storage

If you published migrations, you can store SEO data in the database:

```php
use Salehye\Seo\Models\SeoMetadata;

// Create or update SEO for a model
SeoMetadata::createOrUpdateForModel($post, [
    'title' => 'SEO Title',
    'description' => 'SEO Description',
    'meta_keywords' => 'keyword1, keyword2',
    'og_image' => 'image.jpg',
    'canonical_url' => route('post.show', $post),
    'schema_data' => [...],
]);

// Get SEO for a model
$seo = SeoMetadata::forModel($post)->first();
$seoArray = $seo->toSeoArray();
```

## Caching

SEO data is automatically cached. Configure in `config/seo.php`:

```php
'cache_enabled' => true,
'cache_ttl' => 3600, // 1 hour
'cache_prefix' => 'seo_',
```

Use caching with models:

```php
// Generate with cache (1 hour TTL)
$seo = $post->generateSeoWithCache(3600);

// Clear cache
$post->clearSeoCache();
```

## Multi-language Support

```php
// Set alternate languages
Seo::alternate('en', route('page.en'))
    ->alternate('ar', route('page.ar'))
    ->alternate('fr', route('page.fr'));

// Or in model
protected function seoConfig(): array
{
    return [
        'title' => $this->getTranslation('title', app()->getLocale()),
        // ...
    ];
}
```

## Analytics Integration

Add analytics IDs to `.env`:

```env
SEO_GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
SEO_GOOGLE_TAG_MANAGER_ID=GTM-XXXXXXX
SEO_GOOGLE_SITE_VERIFICATION=xxxxxxxxxxxxx
SEO_BING_SITE_VERIFICATION=xxxxxxxxxxxxx
SEO_FACEBOOK_DOMAIN_VERIFICATION=xxxxxxxxxxxxx
```

## Examples

### E-commerce Product

```php
class Product extends Model
{
    use HasSeo;
    
    protected function seoConfig(): array
    {
        return [
            'title' => $this->name,
            'description' => $this->short_description,
            'keywords' => $this->tags?->pluck('name')->join(', '),
            'image' => $this->main_image,
            'type' => 'product',
            
            'schemas' => [
                [
                    'type' => 'Product',
                    'name' => $this->name,
                    'description' => $this->description,
                    'image' => $this->main_image,
                    'price' => $this->price,
                    'currency' => 'SAR',
                    'inStock' => $this->in_stock,
                    'sku' => $this->sku,
                    'brand' => $this->brand->name,
                    'rating' => [
                        'value' => $this->averageRating(),
                        'count' => $this->reviews_count,
                    ],
                ],
            ],
        ];
    }
}
```

### Service Page

```php
class Service extends Model
{
    use HasSeo;
    
    protected function seoConfig(): array
    {
        return [
            'title' => $this->name,
            'description' => $this->description,
            'image' => $this->image,
            'type' => 'service',
            
            'schemas' => [
                [
                    'type' => 'Service',
                    'name' => $this->name,
                    'description' => $this->description,
                    'areaServed' => ['@type' => 'City', 'name' => $this->city],
                    'offers' => [
                        'price' => $this->starting_price,
                        'currency' => 'SAR',
                    ],
                ],
                $this->faqs ? [
                    'type' => 'FAQ',
                    'faqs' => $this->faqs,
                ] : null,
            ],
        ];
    }
}
```

### Blog Post

```php
class Post extends Model
{
    use HasSeo;
    
    protected function seoConfig(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->excerpt,
            'keywords' => $this->tags?->pluck('name')->join(', '),
            'image' => $this->featured_image,
            'type' => 'article',
            
            'meta' => [
                'author' => $this->author->name,
                'published_date' => $this->published_at->format('Y-m-d'),
                'category' => $this->category->name,
            ],
            
            'schemas' => [
                [
                    'type' => 'BlogPosting',
                    'headline' => $this->title,
                    'description' => $this->excerpt,
                    'author' => $this->author->name,
                    'publishedAt' => $this->published_at->toIso8601String(),
                    'modifiedAt' => $this->updated_at->toIso8601String(),
                    'image' => $this->featured_image,
                ],
            ],
        ];
    }
}
```

## Testing

```bash
composer test
```

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For issues and feature requests, please create an issue on GitHub.

---

Made with ❤️ by [Saleh](https://github.com/salehye)
