{{-- SEO Blade Component --}}
@props(['seo' => []])

@php
    $seoData = $seo ?: (isset($page['props']['seo']) ? $page['props']['seo'] : []);
    $defaultImage = asset(config('seo.default_image', 'images/default-og-image.jpg'));
    
    // Merge provided SEO data with component properties
    $title = $seoData['title'] ?? $title ?? config('seo.default_title', config('app.name'));
    $description = $seoData['description'] ?? $description ?? config('seo.default_description', '');
    $keywords = $seoData['keywords'] ?? $keywords ?? config('seo.default_keywords', '');
    $image = $seoData['image'] ?? $image ?? $defaultImage;
    $canonical = $seoData['canonical'] ?? $canonical ?? url()->current();
    $robots = $seoData['robots'] ?? $robots ?? 'index, follow';
    $alternateLanguages = $seoData['alternate_languages'] ?? $alternateLanguages ?? [];
    $structuredData = $seoData['structured_data'] ?? $structuredData ?? [];
    $ogType = $seoData['og_type'] ?? 'website';
    $siteName = $seoData['site_name'] ?? config('seo.site_name', config('app.name'));
    $locale = $seoData['locale'] ?? app()->getLocale();
    $twitterCard = $seoData['twitter_card'] ?? 'summary_large_image';
    $twitterHandle = $seoData['twitter_handle'] ?? config('seo.twitter_handle');
    
    // Convert relative image paths to absolute URLs
    if ($image && !filter_var($image, FILTER_VALIDATE_URL)) {
        $image = asset($image);
    }
@endphp

{{-- Title --}}
<title>{{ $title }}</title>

{{-- Basic Meta Tags --}}
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="robots" content="{{ $robots }}">
<meta name="viewport" content="width=device-width, initial-scale=1">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonical }}">

{{-- Alternate Languages --}}
@foreach($alternateLanguages as $lang => $url)
    <link rel="alternate" hreflang="{{ $lang }}" href="{{ $url }}">
@endforeach

{{-- Favicon --}}
@if(config('seo.favicon'))
    <link rel="icon" href="{{ asset(config('seo.favicon')) }}" type="image/x-icon">
@endif

{{-- Open Graph --}}
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ $locale }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">

{{-- Twitter Card --}}
<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
@if($twitterHandle)
    <meta name="twitter:site" content="{{ $twitterHandle }}">
@endif

{{-- Facebook App ID --}}
@if(config('seo.facebook_app_id'))
    <meta property="fb:app_id" content="{{ config('seo.facebook_app_id') }}">
@endif

{{-- Site Verification Codes --}}
@if(config('seo.google_site_verification'))
    <meta name="google-site-verification" content="{{ config('seo.google_site_verification') }}">
@endif

@if(config('seo.bing_site_verification'))
    <meta name="msvalidate.01" content="{{ config('seo.bing_site_verification') }}">
@endif

@if(config('seo.facebook_domain_verification'))
    <meta name="facebook-domain-verification" content="{{ config('seo.facebook_domain_verification') }}">
@endif

{{-- Structured Data (JSON-LD) --}}
@foreach($structuredData as $schema)
    @if(!empty($schema))
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endif
@endforeach

{{-- Google Analytics --}}
@if(config('seo.google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('seo.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('seo.google_analytics_id') }}');
    </script>
@endif

{{-- Google Tag Manager --}}
@if(config('seo.google_tag_manager_id'))
    <script>
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','{{ config('seo.google_tag_manager_id') }}');
    </script>
@endif
