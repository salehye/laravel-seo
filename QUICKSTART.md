# 🚀 دليل الاستخدام السريع - Laravel SEO Package

## التثبيت السريع

```bash
# 1. تثبيت الحزمة
composer require salehye/laravel-seo

# 2. تثبيت تلقائي (ينشر كل شيء)
php artisan seo:install
```

## الاستخدام في 3 خطوات فقط

### الخطوة 1: أضف الـ Trait للنموذج

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
            'image' => $this->featured_image,
            'type' => 'article',
        ];
    }
}
```

### الخطوة 2: استخدمه في الـ Controller

```php
public function show(Post $post)
{
    return view('posts.show', [
        'post' => $post,
        'seo' => $post->generateSeo(),
    ]);
}
```

### الخطوة 3: أضف الـ Component للـ Layout

```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <x-seo :seo="$seo" />
</head>
<body>
    @yield('content')
</body>
</html>
```

## 📋 أمثلة جاهزة

### 1. منتج (E-commerce)

```php
class Product extends Model
{
    use HasSeo;
    
    protected function seoConfig(): array
    {
        return [
            'title' => $this->name,
            'description' => Str::limit($this->description, 160),
            'keywords' => $this->tags?->pluck('name')->join(', '),
            'image' => $this->main_image,
            'type' => 'product',
            
            'schemas' => [
                [
                    'type' => 'Product',
                    'name' => $this->name,
                    'description' => $this->description,
                    'price' => $this->price,
                    'currency' => 'SAR',
                    'inStock' => $this->in_stock,
                    'sku' => $this->sku,
                    'brand' => $this->brand->name,
                ],
            ],
            
            'breadcrumb' => [
                ['name' => 'الرئيسية', 'url' => url('/')],
                ['name' => 'المنتجات', 'url' => route('products.index')],
                ['name' => $this->name, 'url' => $this->url],
            ],
        ];
    }
}
```

### 2. خدمة (Service)

```php
class Service extends Model
{
    use HasSeo;
    
    protected function seoConfig(): array
    {
        return [
            'title' => "{$this->name} في {$this->city}",
            'description' => $this->description,
            'keywords' => "{$this->name}, {$this->city}, خدمات",
            'image' => $this->image,
            'type' => 'service',
            
            'schemas' => [
                [
                    'type' => 'Service',
                    'name' => $this->name,
                    'description' => $this->description,
                    'areaServed' => ['@type' => 'City', 'name' => $this->city],
                    'offers' => [
                        'price' => $this->price,
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

### 3. مقال (Blog Post)

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
                    'image' => $this->featured_image,
                ],
            ],
            
            'breadcrumb' => [
                ['name' => 'الرئيسية', 'url' => url('/')],
                ['name' => 'المدونة', 'url' => route('blog.index')],
                ['name' => $this->title, 'url' => $this->url],
            ],
        ];
    }
}
```

### 4. صفحة هبوط (Landing Page)

```php
// في الـ Controller
use Salehye\Seo\Facades\Seo;

public function landing()
{
    $seo = Seo::title('عرض خاص - احجز الآن')
        ->description('احصل على خصم 50% على جميع الخدمات')
        ->keywords(['عرض', 'خصم', 'تخفيضات'])
        ->image(asset('images/offer.jpg'))
        ->canonical(route('landing'))
        ->addOrganizationSchema()
        ->addFaqSchema([
            ['question' => 'كم مدة العرض؟', 'answer' => 'حتى نهاية الشهر'],
            ['question' => 'كيف أحجز؟', 'answer' => 'اتصل على 0556558989'],
        ])
        ->generate();
    
    return view('landing', ['seo' => $seo]);
}
```

## 🎯 استخدام الـ Facade مباشرة

```php
use Salehye\Seo\Facades\Seo;

// طريقة 1: سلسلة الدوال
Seo::title('عنوان الصفحة')
    ->description('وصف الصفحة')
    ->keywords(['كلمة1', 'كلمة2'])
    ->canonical(url()->current())
    ->image(asset('image.jpg'))
    ->addOrganizationSchema()
    ->addWebsiteSchema()
    ->addBreadcrumbSchema([
        ['name' => 'الرئيسية', 'url' => url('/')],
        ['name' => 'من نحن', 'url' => route('about')],
    ]);

$seo = Seo->generate();

// طريقة 2: استخدام الدوال المساعدة
seo_title('عنوان الصفحة');
seo_description('وصف الصفحة');
seo_keywords(['كلمة1', 'كلمة2']);
seo_image('image.jpg');
seo_canonical(route('page'));

$seo = seo_render();
```

## 🤖 أوامر Console

```bash
# تثبيت الحزمة
php artisan seo:install

# مسح الكاش
php artisan seo:clear-cache

# توليد sitemap
php artisan seo:generate-sitemap
```

## 📊 أنواع الـ Schemas المدعومة

```php
// Organization
Seo::addOrganizationSchema([...]);

// Website
Seo::addWebsiteSchema([...]);

// Service
Seo::addServiceSchema([...]);

// Product
Seo::addProductSchema([...]);

// Article/BlogPosting/NewsArticle
Seo::addArticleSchema([...]);
Seo::addBlogPostingSchema([...]);

// FAQ
Seo::addFaqSchema([
    ['question' => 'س1', 'answer' => 'ج1'],
    ['question' => 'س2', 'answer' => 'ج2'],
]);

// AggregateRating
Seo::addAggregateRatingSchema(4.5, 100); // rating, count

// Breadcrumb
Seo::addBreadcrumbSchema([
    ['name' => 'الرئيسية', 'url' => url('/')],
    ['name' => 'القسم', 'url' => route('category')],
    ['name' => 'الصفحة', 'url' => url()->current()],
]);

// Event
Seo::addEventSchema([...]);

// LocalBusiness
Seo::addLocalBusinessSchema([...]);

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

## 🌐 Multi-language

```php
// إضافة لغات بديلة
Seo::alternate('en', route('page.en'))
    ->alternate('ar', route('page.ar'))
    ->alternate('fr', route('page.fr'));

// في النموذج
protected function seoConfig(): array
{
    return [
        'title' => $this->getTranslation('title', app()->getLocale()),
        'description' => $this->getTranslation('description', app()->getLocale()),
        // ...
    ];
}
```

## 💾 التخزين في قاعدة البيانات

```bash
# نشر الـ Migration
php artisan vendor:publish --provider="Salehye\Seo\Providers\SeoServiceProvider" --tag="seo-migrations"
php artisan migrate
```

```php
use Salehye\Seo\Models\SeoMetadata;

// حفظ SEO
SeoMetadata::createOrUpdateForModel($post, [
    'title' => 'عنوان SEO',
    'description' => 'وصف SEO',
    'meta_keywords' => 'كلمة1, كلمة2',
    'og_image' => 'image.jpg',
    'canonical_url' => route('post.show', $post),
]);

// جلب SEO
$seo = SeoMetadata::forModel($post)->first();
$seoArray = $seo->toSeoArray();
```

## ⚡ الت caching

```php
// توليد مع الكاش
$seo = $post->generateSeoWithCache(3600); // 1 ساعة

// مسح كاش صفحة معينة
$post->clearSeoCache();

// مسح كل كاش SEO
php artisan seo:clear-cache
```

## 🔗 الروبوتات وملفات SEO

```
/sitemap.xml  - خريطة الموقع
/robots.txt   - ملف الروبوتات
/sitemap.xsl  - تنسيق خريطة الموقع
```

## ⚙️ الإعدادات في .env

```env
SEO_SITE_NAME=اسم موقعك
SEO_SITE_URL=https://example.com
SEO_DEFAULT_TITLE=الصفحة الرئيسية
SEO_DEFAULT_DESCRIPTION=وصف الموقع
SEO_DEFAULT_IMAGE=images/default.jpg
SEO_TWITTER_HANDLE=@yourhandle
SEO_GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
SEO_GOOGLE_SITE_VERIFICATION=xxxxxxxxxxxxx
```

---

## 📞 للدعم

للأسئلة والمشاكل، يرجى إنشاء issue على GitHub.

**Made with ❤️ by [Saleh](https://github.com/salehye)**
