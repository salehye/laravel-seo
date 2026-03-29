<?php

namespace Salehye\Seo\View\Components;

use Illuminate\View\Component;

class Seo extends Component
{
    public array $seo;
    public ?string $title;
    public ?string $description;
    public ?string $keywords;
    public ?string $image;
    public ?string $canonical;
    public ?string $robots;
    public array $alternateLanguages;
    public array $structuredData;
    public array $openGraph;
    public array $twitterCard;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?array $seo = null,
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $image = null,
        ?string $canonical = null,
        ?string $robots = null,
        array $alternateLanguages = [],
        array $structuredData = [],
        array $openGraph = [],
        array $twitterCard = []
    ) {
        // If SEO data is provided, use it
        if ($seo) {
            $this->seo = $seo;
            $this->title = $seo['title'] ?? null;
            $this->description = $seo['description'] ?? null;
            $this->keywords = $seo['keywords'] ?? null;
            $this->image = $seo['image'] ?? null;
            $this->canonical = $seo['canonical'] ?? null;
            $this->robots = $seo['robots'] ?? null;
            $this->alternateLanguages = $seo['alternate_languages'] ?? [];
            $this->structuredData = $seo['structured_data'] ?? [];
            $this->openGraph = $seo['open_graph'] ?? [];
            $this->twitterCard = $seo['twitter_card'] ?? [];
        } else {
            // Otherwise, use individual parameters
            $this->title = $title;
            $this->description = $description;
            $this->keywords = $keywords;
            $this->image = $image;
            $this->canonical = $canonical ?? url()->current();
            $this->robots = $robots ?? 'index, follow';
            $this->alternateLanguages = $alternateLanguages;
            $this->structuredData = $structuredData;
            $this->openGraph = $openGraph;
            $this->twitterCard = $twitterCard;
        }

        // Apply defaults
        $this->title ??= config('seo.default_title', config('app.name'));
        $this->description ??= config('seo.default_description', '');
        $this->keywords ??= config('seo.default_keywords', '');
        $this->image ??= config('seo.default_image', 'images/default-og-image.jpg');
        
        // Convert relative image paths to absolute URLs
        if ($this->image && !filter_var($this->image, FILTER_VALIDATE_URL)) {
            $this->image = asset($this->image);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('seo::components.seo');
    }

    /**
     * Get Open Graph image with dimensions
     */
    public function getOgImage(): ?string
    {
        return $this->image;
    }

    /**
     * Get Twitter card type
     */
    public function getTwitterCardType(): string
    {
        return $this->twitterCard['card'] ?? 'summary_large_image';
    }

    /**
     * Get site name
     */
    public function getSiteName(): string
    {
        return config('seo.site_name', config('app.name'));
    }

    /**
     * Get Twitter handle
     */
    public function getTwitterHandle(): ?string
    {
        return $this->twitterCard['site'] ?? config('seo.twitter_handle');
    }

    /**
     * Get Facebook App ID
     */
    public function getFacebookAppId(): ?string
    {
        return config('seo.facebook_app_id');
    }

    /**
     * Get locale
     */
    public function getLocale(): string
    {
        return app()->getLocale();
    }

    /**
     * Get verification codes
     */
    public function getVerificationCodes(): array
    {
        return [
            'google' => config('seo.google_site_verification'),
            'bing' => config('seo.bing_site_verification'),
            'facebook' => config('seo.facebook_domain_verification'),
        ];
    }

    /**
     * Get analytics IDs
     */
    public function getAnalyticsIds(): array
    {
        return [
            'google_analytics' => config('seo.google_analytics_id'),
            'google_tag_manager' => config('seo.google_tag_manager_id'),
        ];
    }
}
