<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Site Information
    |--------------------------------------------------------------------------
    */
    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'My Website')),
    'site_url' => env('SEO_SITE_URL', env('APP_URL', 'https://example.com')),
    'default_title' => env('SEO_DEFAULT_TITLE', 'Home'),
    'default_description' => env('SEO_DEFAULT_DESCRIPTION', 'Default website description'),
    'default_image' => env('SEO_DEFAULT_IMAGE', 'images/default-og-image.jpg'),
    'default_keywords' => env('SEO_DEFAULT_KEYWORDS', 'website, laravel, seo'),

    /*
    |--------------------------------------------------------------------------
    | Social Media Settings
    |--------------------------------------------------------------------------
    */
    'twitter_handle' => env('SEO_TWITTER_HANDLE', '@website'),
    'facebook_app_id' => env('SEO_FACEBOOK_APP_ID', null),
    'instagram_handle' => env('SEO_INSTAGRAM_HANDLE', 'website'),
    'linkedin_url' => env('SEO_LINKEDIN_URL', null),
    'youtube_url' => env('SEO_YOUTUBE_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Robots Settings
    |--------------------------------------------------------------------------
    */
    'robots' => env('SEO_ROBOTS', 'index, follow'),
    'robots_disallow' => [
        '/admin/*',
        '/dashboard/*',
        '/api/*',
        '/auth/*',
        '/login',
        '/register',
        '/password-reset',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Settings
    |--------------------------------------------------------------------------
    */
    'sitemap' => [
        'enabled' => env('SEO_SITEMAP_ENABLED', true),
        'frequency' => env('SEO_SITEMAP_FREQUENCY', 'daily'),
        'priority' => env('SEO_SITEMAP_PRIORITY', 0.8),
        'static_pages' => [
            '/' => ['priority' => 1.0, 'frequency' => 'daily'],
            '/about' => ['priority' => 0.8, 'frequency' => 'monthly'],
            '/contact' => ['priority' => 0.7, 'frequency' => 'monthly'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    'cache_enabled' => env('SEO_CACHE_ENABLED', true),
    'cache_ttl' => env('SEO_CACHE_TTL', 3600),
    'cache_prefix' => env('SEO_CACHE_PREFIX', 'seo_'),

    /*
    |--------------------------------------------------------------------------
    | Localization Settings
    |--------------------------------------------------------------------------
    */
    'country' => env('SEO_COUNTRY', 'US'),
    'language' => env('SEO_LANGUAGE', 'en'),
    'phone' => env('SEO_PHONE', null),
    'email' => env('SEO_EMAIL', 'info@example.com'),
    'address' => env('SEO_ADDRESS', null),

    /*
    |--------------------------------------------------------------------------
    | Social Links
    |--------------------------------------------------------------------------
    */
    'social_links' => [
        'twitter' => env('SEO_TWITTER_URL', null),
        'instagram' => env('SEO_INSTAGRAM_URL', null),
        'facebook' => env('SEO_FACEBOOK_URL', null),
        'youtube' => env('SEO_YOUTUBE_URL', null),
        'linkedin' => env('SEO_LINKEDIN_URL', null),
        'whatsapp' => env('SEO_WHATSAPP_URL', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Segment Names for Breadcrumb
    |--------------------------------------------------------------------------
    */
    'segment_names' => [
        'services' => 'Services',
        'products' => 'Products',
        'blog' => 'Blog',
        'about' => 'About',
        'contact' => 'Contact',
        'faq' => 'FAQ',
        'cities' => 'Cities',
        'offers' => 'Offers',
        'categories' => 'Categories',
        'tags' => 'Tags',
    ],

    /*
    |--------------------------------------------------------------------------
    | Advanced Settings
    |--------------------------------------------------------------------------
    */
    'auto_alternates' => env('SEO_AUTO_ALTERNATES', true),
    'auto_canonical' => env('SEO_AUTO_CANONICAL', true),
    'add_organization_schema' => env('SEO_ADD_ORGANIZATION_SCHEMA', true),
    'add_website_schema' => env('SEO_ADD_WEBSITE_SCHEMA', true),
    'add_breadcrumb_schema' => env('SEO_ADD_BREADCRUMB_SCHEMA', true),

    /*
    |--------------------------------------------------------------------------
    | Logo Settings
    |--------------------------------------------------------------------------
    */
    'logo' => env('SEO_LOGO', 'images/logo.png'),
    'logo_width' => env('SEO_LOGO_WIDTH', 600),
    'logo_height' => env('SEO_LOGO_HEIGHT', 60),

    /*
    |--------------------------------------------------------------------------
    | Analytics Integration
    |--------------------------------------------------------------------------
    */
    'google_analytics_id' => env('SEO_GOOGLE_ANALYTICS_ID', null),
    'google_tag_manager_id' => env('SEO_GOOGLE_TAG_MANAGER_ID', null),
    'google_site_verification' => env('SEO_GOOGLE_SITE_VERIFICATION', null),
    'bing_site_verification' => env('SEO_BING_SITE_VERIFICATION', null),
    'facebook_domain_verification' => env('SEO_FACEBOOK_DOMAIN_VERIFICATION', null),

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    */
    'use_database' => env('SEO_USE_DATABASE', false),
    'seo_table' => 'seo_metadata',
];
