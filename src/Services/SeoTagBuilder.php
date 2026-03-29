<?php

namespace Salehye\Seo\Services;

use Illuminate\Support\Str;

class SeoTagBuilder
{
    protected array $tags = [];
    protected array $structuredData = [];
    protected array $openGraph = [];
    protected array $twitterCard = [];
    protected array $links = [];
    protected array $additionalMeta = [];

    /**
     * Set page title
     */
    public function title(string $title, ?string $suffix = null): self
    {
        $siteName = config('seo.site_name');
        $this->tags['title'] = $suffix 
            ? "{$title} | {$suffix}" 
            : (Str::contains($title, '|') ? $title : "{$title} | {$siteName}");
        return $this;
    }

    /**
     * Set meta description
     */
    public function description(string $description, int $limit = 160): self
    {
        $this->tags['description'] = Str::limit($description, $limit);
        return $this;
    }

    /**
     * Set meta keywords
     */
    public function keywords(string|array $keywords): self
    {
        $this->tags['keywords'] = is_array($keywords) 
            ? implode(', ', array_unique($keywords)) 
            : $keywords;
        return $this;
    }

    /**
     * Set robots meta tag
     */
    public function robots(string $robots): self
    {
        $this->tags['robots'] = $robots;
        return $this;
    }

    /**
     * Set author meta tag
     */
    public function author(string $author): self
    {
        $this->tags['author'] = $author;
        return $this;
    }

    /**
     * Set canonical URL
     */
    public function canonical(string $url): self
    {
        $this->links['canonical'] = $url;
        return $this;
    }

    /**
     * Set alternate language URL
     */
    public function alternate(string $lang, string $url): self
    {
        $this->links['alternate'][$lang] = $url;
        return $this;
    }

    /**
     * Set multiple alternate languages
     */
    public function alternates(array $languages): self
    {
        foreach ($languages as $lang => $url) {
            $this->alternate($lang, $url);
        }
        return $this;
    }

    /**
     * Add Open Graph tag
     */
    public function og(string $property, string $content): self
    {
        $this->openGraph[$property] = $content;
        return $this;
    }

    /**
     * Set basic Open Graph tags
     */
    public function basicOpenGraph(
        string $title, 
        string $description, 
        string $url, 
        string $image
    ): self {
        return $this->og('title', $title)
            ->og('description', $description)
            ->og('url', $url)
            ->og('image', $image)
            ->og('type', 'website')
            ->og('site_name', config('seo.site_name'));
    }

    /**
     * Set Open Graph image with optional dimensions and alt
     */
    public function imageOpenGraph(
        string $url, 
        ?string $alt = null, 
        ?int $width = null, 
        ?int $height = null
    ): self {
        $this->og('image', $url);
        if ($alt) $this->og('image:alt', $alt);
        if ($width) $this->og('image:width', $width);
        if ($height) $this->og('image:height', $height);
        return $this;
    }

    /**
     * Add Twitter Card tag
     */
    public function twitter(string $name, string $content): self
    {
        $this->twitterCard[$name] = $content;
        return $this;
    }

    /**
     * Set basic Twitter Card tags
     */
    public function basicTwitterCard(
        string $title, 
        string $description, 
        string $image, 
        string $card = 'summary_large_image'
    ): self {
        return $this->twitter('card', $card)
            ->twitter('title', $title)
            ->twitter('description', $description)
            ->twitter('image', $image)
            ->twitter('site', config('seo.twitter_handle', '@website'));
    }

    /**
     * Add structured data
     */
    public function schema(array $data, ?string $key = null): self
    {
        if ($key) {
            $this->structuredData[$key] = $data;
        } else {
            $this->structuredData[] = $data;
        }
        return $this;
    }

    /**
     * Add additional meta tag
     */
    public function addMeta(string $name, string $content, string $type = 'name'): self
    {
        $this->additionalMeta["{$type}_{$name}"] = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
        ];
        return $this;
    }

    /**
     * Build all tags into array
     */
    public function build(): array
    {
        return [
            'title' => $this->tags['title'] ?? null,
            'description' => $this->tags['description'] ?? null,
            'keywords' => $this->tags['keywords'] ?? null,
            'robots' => $this->tags['robots'] ?? 'index, follow',
            'author' => $this->tags['author'] ?? null,
            'links' => $this->links,
            'open_graph' => $this->openGraph,
            'twitter_card' => $this->twitterCard,
            'structured_data' => array_values($this->structuredData),
            'additional_meta' => $this->additionalMeta,
        ];
    }

    /**
     * Convert to HTML string
     */
    public function toHtml(): string
    {
        $html = [];
        $data = $this->build();

        // Title
        if ($data['title']) {
            $html[] = "<title>{$data['title']}</title>";
        }

        // Basic Meta Tags
        $basicMeta = ['description', 'keywords', 'robots', 'author'];
        foreach ($basicMeta as $meta) {
            if ($data[$meta]) {
                $html[] = "<meta name=\"{$meta}\" content=\"{$data[$meta]}\">";
            }
        }

        // Canonical Link
        if (isset($data['links']['canonical'])) {
            $html[] = "<link rel=\"canonical\" href=\"{$data['links']['canonical']}\">";
        }

        // Alternate Languages
        if (isset($data['links']['alternate'])) {
            foreach ($data['links']['alternate'] as $lang => $url) {
                $html[] = "<link rel=\"alternate\" hreflang=\"{$lang}\" href=\"{$url}\">";
            }
        }

        // Additional Meta Tags
        foreach ($data['additional_meta'] as $meta) {
            $html[] = "<meta {$meta['type']}=\"{$meta['name']}\" content=\"{$meta['content']}\">";
        }

        // Open Graph
        foreach ($data['open_graph'] as $property => $content) {
            if ($content) {
                $html[] = "<meta property=\"og:{$property}\" content=\"{$content}\">";
            }
        }

        // Twitter Card
        foreach ($data['twitter_card'] as $name => $content) {
            if ($content) {
                $html[] = "<meta name=\"twitter:{$name}\" content=\"{$content}\">";
            }
        }

        // Structured Data (JSON-LD)
        foreach ($data['structured_data'] as $schema) {
            $json = json_encode(
                $schema, 
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
            );
            $html[] = "<script type=\"application/ld+json\">\n{$json}\n</script>";
        }

        return implode("\n    ", $html);
    }

    /**
     * Get structured data as array
     */
    public function getStructuredData(): array
    {
        return array_values($this->structuredData);
    }

    /**
     * Clear all tags
     */
    public function clear(): self
    {
        $this->tags = [];
        $this->structuredData = [];
        $this->openGraph = [];
        $this->twitterCard = [];
        $this->links = [];
        $this->additionalMeta = [];
        return $this;
    }
}
