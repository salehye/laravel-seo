# 📁 هيكل ملفات حزمة Laravel SEO

## نظرة عامة على الهيكل

```
laravel-seo/
├── 📄 composer.json                  # ملف تعريف الحزمة والتبعيات
├── 📄 README.md                      # التوثيق الرئيسي (إنجليزي)
├── 📄 QUICKSTART.md                  # دليل البدء السريع (عربي)
├── 📄 FILES_GUIDE.md                 # هذا الملف - شرح جميع الملفات
├── 📄 LICENSE                        # رخصة MIT
├── 📄 .gitignore                     # ملفات Git المستبعدة
├── 📄 phpunit.xml                    # إعدادات PHPUnit للاختبارات
│
├── 📁 src/                           # الكود المصدري الرئيسي
│   ├── 📁 Services/
│   │   ├── SeoService.php           # الخدمة الرئيسية - الواجهة الأساسية
│   │   ├── SeoTagBuilder.php        # بناء Meta Tags (HTML)
│   │   └── SchemaBuilder.php        # بناء Schemas (JSON-LD)
│   │
│   ├── 📁 Traits/
│   │   └── HasSeo.php               # Trait يضاف للنماذج
│   │
│   ├── 📁 Facades/
│   │   └── Seo.php                  # Facade للاستخدام السهل
│   │
│   ├── 📁 Models/
│   │   └── SeoMetadata.php          # Model لقاعدة البيانات
│   │
│   ├── 📁 View/Components/
│   │   └── Seo.php                  # Blade Component Class
│   │
│   ├── 📁 Providers/
│   │   └── SeoServiceProvider.php   # مزود الخدمة - التسجيل والتحميل
│   │
│   ├── 📁 Commands/
│   │   ├── InstallCommand.php       # أمر التثبيت
│   │   ├── ClearCacheCommand.php    # أمر مسح الكاش
│   │   └── SitemapGeneratorCommand.php # أمر توليد sitemap
│   │
│   └── 📁 Helpers/
│       └── seo_helpers.php          # دوال مساعدة عالمية
│
├── 📁 config/
│   └── seo.php                      # ملف الإعدادات
│
├── 📁 resources/views/components/
│   └── seo.blade.php               # Blade Component View
│
├── 📁 routes/
│   └── web.php                     # Routes لـ sitemap و robots.txt
│
├── 📁 database/migrations/
│   └── create_seo_metadata_table.php # Migration لجدول SEO
│
├── 📁 tests/
│   └── SeoServiceTest.php          # اختبارات الوحدة
│
└── 📁 stubs/
    └── seo-config.stub             # قالب إعدادات SEO للنماذج
```

---

## 📋 شرح تفصيلي لكل ملف

### 1. ملفات التكوين الأساسية

#### `composer.json`
```
تعريف الحزمة لـ Composer:
- الاسم: salehye/laravel-seo
- التبعيات: Laravel 10/11/12, PHP 8.1+
- Autoload: PSR-4 للدوال والملفات
- Service Providers و Facades التلقائية
```

#### `config/seo.php`
```
الإعدادات الرئيسية:
- معلومات الموقع (الاسم، URL، العنوان الافتراضي)
- إعدادات التواصل الاجتماعي
- إعدادات الروبوتات و Sitemap
- إعدادات الكاش
- اللغات والإعدادات المحلية
- رموز التحقق (Google, Bing, Facebook)
- Google Analytics و Tag Manager
```

---

### 2. الخدمات الأساسية (Services)

#### `src/Services/SeoService.php`
```
الخدمة الرئيسية - الواجهة الأساسية للحزمة:

الدوال الأساسية:
- title() - تعيين العنوان
- description() - تعيين الوصف
- keywords() - تعيين الكلمات المفتاحية
- canonical() - تعيين URLcanonical
- image() - تعيين الصورة
- robots() - تعيين إعدادات الروبوتات

Schemas:
- addOrganizationSchema()
- addWebsiteSchema()
- addServiceSchema()
- addProductSchema()
- addArticleSchema()
- addFaqSchema()
- addBreadcrumbSchema()
- ...و 10 أنواع أخرى

التوليد:
- generate() - توليد بيانات SEO كـ array
- toHtml() - توليد HTML جاهز
- forInertia() - توليد لـ Inertia.js
- forBlade() - توليد لـ Blade
```

#### `src/Services/SeoTagBuilder.php`
```
بناء Meta Tags HTML:

المسؤول عن:
- بناء<title>,<meta> tags
- بناء<link rel="canonical">
- بناء<link rel="alternate"> للغات
- بناء<meta property="og:*"> لـ Open Graph
- بناء<meta name="twitter:*"> لـ Twitter Cards
- بناء<script type="application/ld+json"> للـ Schemas

الطرق:
- build() - بناء array من جميع tags
- toHtml() - تحويل إلى HTML string
- clear() - مسح جميع tags
```

#### `src/Services/SchemaBuilder.php`
```
بناء Structured Data (JSON-LD):

أنواع Schemas المدعومة:
1. Organization - معلومات المؤسسة
2. Website - معلومات الموقع
3. WebPage - معلومات الصفحة
4. LocalBusiness - الأعمال المحلية
5. Service - الخدمات
6. Product - المنتجات
7. Article/BlogPosting/NewsArticle - المقالات
8. FAQ - الأسئلة الشائعة
9. AggregateRating - التقييمات
10. Breadcrumb - التنقل
11. ItemList - القوائم
12. Event - الفعاليات
13. City - المدن
14. Person - الأشخاص
15. Video - الفيديوهات
16. Recipe - الوصفات
17. JobPosting - الوظائف
18. Course - الدورات

كل دالة ترجع array جاهز بصيغة Schema.org
```

---

### 3. الـ Trait (HasSeo)

#### `src/Traits/HasSeo.php`
```
يُضاف للنماذج لإضافة وظائف SEO:

الاستخدام:
```php
class Post extends Model {
    use HasSeo;
    
    protected function seoConfig(): array {
        return [...];
    }
}
```

الدوال المضافة:
- generateSeo() - توليد SEO كامل
- generateSeoWithCache() - توليد مع الكاش
- clearSeoCache() - مسح الكاش
- seo() - الحصول على خدمة SEO

دوال مساعدة:
- seoConfig() - تكوين SEO (يجب تنفيذه في النموذج)
- getCacheKey() - مفتاح الكاش
- formatTitle() - تنسيق العنوان
- getImageUrl() - الحصول على URL الصورة
- getCanonicalUrl() - الحصول على canonical URL
- getModelUrl() - الحصول على URL النموذج
```

---

### 4. الـ Facade

#### `src/Facades/Seo.php`
```
واجهة سهلة للاستخدام:

مثال:
```php
use Salehye\Seo\Facades\Seo;

Seo::title('Page')
    ->description('Desc')
    ->addOrganizationSchema()
    ->generate();
```

جميع الدوال في SeoService متاحة عبر الـ Facade
```

---

### 5. الـ Model

#### `src/Models/SeoMetadata.php`
```
للتخزين في قاعدة البيانات:

الأعمدة:
- model_type, model_id - للـ Polymorphic Relation
- title, description, keywords - بيانات SEO الأساسية
- meta_title, meta_description, meta_keywords
- og_image, twitter_image, canonical_url
- robots, no_index, no_follow
- schema_data, og_data, twitter_data (JSON)
- locale, language

الدوال:
- model() - العلاقة مع النموذج
- forModel() - scope للبحث عن نموذج
- createOrUpdateForModel() - إنشاء أو تحديث
- toSeoArray() - تحويل إلى array للاستخدام
```

---

### 6. الـ Commands

#### `src/Commands/InstallCommand.php`
```
أمر التثبيت:
```bash
php artisan seo:install
```

ينشر:
- config/seo.php
- migrations
- views
يضيف متغيرات .env.example
```

#### `src/Commands/ClearCacheCommand.php`
```
مسح كاش SEO:
```bash
php artisan seo:clear-cache
```
```

#### `src/Commands/SitemapGeneratorCommand.php`
```
توليد sitemap.xml ثابت:
```bash
php artisan seo:generate-sitemap --output=public/sitemap.xml
```

يضيف:
- الصفحات الثابتة من config
- روابط النماذج التي تستخدم HasSeo
```

---

### 7. الـ Provider

#### `src/Providers/SeoServiceProvider.php`
```
تسجيل الحزمة في Laravel:

في register():
- دمج الإعدادات
- ربط SeoService بـ container
- تسجيل الـ Commands

في boot():
- نشر config, migrations, views
- تسجيل Blade Component
- تحميل routes
```

---

### 8. الـ Helpers

#### `src/Helpers/seo_helpers.php`
```
دوال مساعدة عالمية:

دوال Get/Set:
- seo_title()
- seo_description()
- seo_keywords()
- seo_image()
- seo_canonical()
- seo_robots()

دوال Schemas:
- seo_schema()
- seo_organization_schema()
- seo_website_schema()
- seo_breadcrumb_schema()
- seo_faq_schema()
- seo_article_schema()
- seo_product_schema()
- seo_aggregate_rating_schema()

دوال Open Graph & Twitter:
- seo_og()
- seo_twitter()

دوال Utility:
- seo_render() - توليد HTML
- seo_default_image()
- seo_site_name()
- seo_social_links()
- seo_verification_codes()
- seo_analytics_ids()
- seo_sitemap_url()
- seo_robots_url()
```

---

### 9. الـ Blade Component

#### `src/View/Components/Seo.php`
```
Class الـ Blade Component:

الخصائص:
- $seo - بيانات SEO كاملة
- $title, $description, $keywords
- $image, $canonical, $robots
- $alternateLanguages
- $structuredData
- $openGraph, $twitterCard

يدعم:
- استخدام مباشر: <x-seo :seo="$seo" />
- استخدام مع خصائص: <x-seo title="..." description="..." />
```

#### `resources/views/components/seo.blade.php`
```
View الـ Component:

ينشئ:
- <title> tag
- Meta tags (description, keywords, robots)
- Canonical link
- Alternate language links
- Open Graph meta tags
- Twitter Card meta tags
- Site verification codes
- Structured data (JSON-LD)
- Google Analytics script
- Google Tag Manager script
```

---

### 10. الـ Routes

#### `routes/web.php`
```
Routes تلقائية:

GET /sitemap.xml
- يولد XML sitemap
- يضيف صفحات من config
- يدعم الكاش

GET /robots.txt
- يولد robots.txt ديناميكي
- يضيف Disallow من config
- يضيف Sitemap location

GET /sitemap.xsl
- XSL stylesheet لتنسيق sitemap
- عرض جميل في المتصفح
```

---

### 11. الـ Migration

#### `database/migrations/create_seo_metadata_table.php`
```
إنشاء جدول seo_metadata:

الأعمدة:
- id
- model_type, model_id, model_key
- title, description, keywords
- meta_* fields
- images fields
- canonical_url
- robots settings
- schema_data (JSON)
- og_data (JSON)
- twitter_data (JSON)
- additional_meta (JSON)
- locale, language
- timestamps

Indexes للأداء السريع
```

---

### 12. الـ Tests

#### `tests/SeoServiceTest.php`
```
اختبارات الوحدة:

يختبر:
- إنشاء SeoService
- تعيين العنوان والوصف والكلمات
- توليد HTML
- إضافة Organization schema
- إضافة Breadcrumb schema
- إضافة FAQ schema
```

---

### 13. الـ Stubs

#### `stubs/seo-config.stub`
```
قالب جاهز لـ seoConfig():

مثال كامل يمكن نسخه للنماذج:
- Basic fields
- Meta tags
- Schemas (مع أمثلة)
- Breadcrumb
- OG & Twitter settings
- Robots settings
```

---

## 🎯 كيفية الاستخدام - ملخص سريع

### 1. تثبيت
```bash
composer require salehye/laravel-seo
php artisan seo:install
```

### 2. في النموذج
```php
class Post extends Model {
    use HasSeo;
    
    protected function seoConfig(): array {
        return [
            'title' => $this->title,
            'description' => $this->excerpt,
            'schemas' => [...],
        ];
    }
}
```

### 3. في الـ Controller
```php
$seo = $post->generateSeo();
return view('posts.show', ['post' => $post, 'seo' => $seo]);
```

### 4. في الـ Layout
```blade
<head>
    <x-seo :seo="$seo" />
</head>
```

---

## 📊 الإحصائيات

- **عدد الملفات**: 20+ ملف
- **عدد الدوال**: 100+ دالة
- **أنواع Schemas**: 18 نوع
- **دوال مساعدة**: 25+ دالة
- **أوامر Console**: 3 أوامر
- **يدعم**: Laravel 10, 11, 12
- **يدعم**: PHP 8.1, 8.2, 8.3

---

**تم تطويره بواسطة [Saleh](https://github.com/salehye) ❤️**
