# Changelog

All notable changes to `salehye/laravel-seo` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2026-05-01

### Added
- Support for Laravel 13.
- Support for PHP 8.4.
- Added `applySeo()` method to `HasSeo` trait for easier global application.
- Added base `TestCase` for easier package testing.
- Added `composer` scripts for `test`, `format`, and `test-coverage`.

### Changed
- Refactored `seo_helpers.php` to use a unified singleton state via `app('seo')`.
- Improved consistency between `SeoService` and `HasSeo` trait.
- Updated documentation with modern examples.
- Standardized code style using Laravel Pint.

### Fixed
- Fixed issues with `SeoService` testing environment.

## [1.0.0] - 2024-03-29

### Added

#### Core Features
- **SeoService** - Main SEO service with fluent interface
- **SeoTagBuilder** - Build HTML meta tags
- **SchemaBuilder** - Build JSON-LD structured data (18+ types)
- **HasSeo Trait** - Add SEO functionality to any model
- **Seo Facade** - Easy access to SEO features
- **SeoMetadata Model** - Database storage for SEO data

#### Schema Types (18+)
- Organization Schema
- Website Schema
- WebPage Schema
- LocalBusiness Schema
- Service Schema
- Product Schema
- Article/BlogPosting/NewsArticle Schema
- FAQ Schema
- AggregateRating Schema
- Review Schema
- Breadcrumb Schema
- ItemList Schema
- Event Schema
- City Schema
- Person Schema
- Video Schema
- Recipe Schema
- JobPosting Schema
- Course Schema

#### Meta Tags
- Title with automatic site name suffix
- Description with character limit
- Keywords (array or string)
- Robots (index/follow control)
- Canonical URL
- Alternate language URLs
- Author meta tag
- Open Graph tags (complete)
- Twitter Card tags (complete)

#### Console Commands
- `seo:install` - Install package with one command
- `seo:clear-cache` - Clear SEO cache
- `seo:generate-sitemap` - Generate static sitemap.xml

#### Routes
- `GET /sitemap.xml` - Dynamic sitemap
- `GET /robots.txt` - Dynamic robots.txt
- `GET /sitemap.xsl` - Pretty sitemap viewing

#### Helper Functions (25+)
- `seo()` - Create SEO service instance
- `seo_title()`, `seo_description()`, `seo_keywords()`
- `seo_image()`, `seo_canonical()`, `seo_robots()`
- `seo_schema()`, `seo_organization_schema()`, `seo_website_schema()`
- `seo_breadcrumb_schema()`, `seo_faq_schema()`
- `seo_og()`, `seo_twitter()`
- `seo_render()` - Render all SEO tags
- `seo_default_image()`, `seo_site_name()`
- `seo_social_links()`, `seo_verification_codes()`
- `seo_analytics_ids()`, and more...

#### Blade Component
- `<x-seo :seo="$seo" />` - Easy SEO integration in Blade
- Automatic meta tags rendering
- Support for Inertia.js

#### Configuration
- Complete `config/seo.php` with 50+ options
- Environment variables support
- Social media links configuration
- Robots.txt rules configuration
- Sitemap configuration
- Cache settings
- Analytics integration (Google Analytics, Tag Manager)
- Site verification codes (Google, Bing, Facebook)

#### Database
- Migration for `seo_metadata` table
- Polymorphic relations support
- Multi-language support
- JSON columns for structured data

#### Cache System
- Automatic caching for SEO data
- Configurable TTL
- Cache prefix support
- Cache clearing commands

#### Multi-language Support
- Alternate language URLs
- Locale-specific SEO data
- Translation-ready

#### Documentation
- `README.md` - Comprehensive English documentation
- `QUICKSTART.md` - Quick start guide (Arabic)
- `FILES_GUIDE.md` - Detailed file-by-file guide
- `COMPLETED.md` - Package summary
- `GIT_GUIDE.md` - Git setup guide
- `CHANGELOG.md` - This file

#### Testing
- PHPUnit configuration
- Unit tests for core functionality
- Test examples included

#### Development Tools
- `.gitignore` - Comprehensive ignore rules
- `.gitattributes` - Line endings configuration
- `phpunit.xml` - Test configuration
- `composer.json` - Package definition

### Features Summary
- ✅ 26 files created
- ✅ 5600+ lines of code
- ✅ 100+ functions/methods
- ✅ 18+ schema types
- ✅ 25+ helper functions
- ✅ 3 console commands
- ✅ Full Laravel 10/11/12 support
- ✅ PHP 8.1/8.2/8.3 support

### Technical Details
- PSR-4 autoloading
- Laravel Package best practices
- Service provider registration
- Facade alias configuration
- View components registration
- Route registration
- Migration publishing
- Config publishing
- View publishing

---

## Future Versions (Planned)

### [1.1.0] - Planned
- [ ] SEO analysis/score feature
- [ ] Automatic keyword suggestions
- [ ] Social media preview generator
- [ ] Bulk SEO operations
- [ ] SEO audit reports

### [2.0.0] - Planned
- [ ] AI-powered content optimization
- [ ] Multi-site support
- [ ] Advanced analytics integration
- [ ] Real-time SEO monitoring
- [ ] API for external integrations

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0.0 | 2024-03-29 | Initial release |

---

**Author**: [Saleh](https://github.com/salehye)
**License**: MIT
