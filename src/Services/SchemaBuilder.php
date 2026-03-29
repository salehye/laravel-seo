<?php

namespace Salehye\Seo\Services;

class SchemaBuilder
{
    /**
     * Build Organization Schema
     */
    public static function organization(array $data = []): array
    {
        return array_merge([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('seo.site_name'),
            'url' => config('seo.site_url'),
            'logo' => asset(config('seo.logo', 'images/logo.png')),
            'sameAs' => array_values(array_filter(config('seo.social_links', []))),
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => config('seo.phone'),
                'contactType' => 'customer service',
                'areaServed' => config('seo.country'),
                'availableLanguage' => config('seo.language'),
            ],
        ], $data);
    }

    /**
     * Build Website Schema
     */
    public static function website(array $data = []): array
    {
        return array_merge([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('seo.site_name'),
            'url' => config('seo.site_url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('seo.site_url') . '/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
            'inLanguage' => config('seo.language'),
        ], $data);
    }

    /**
     * Build WebPage Schema
     */
    public static function webPage(array $data): array
    {
        return array_merge([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $data['name'] ?? config('seo.site_name'),
            'description' => $data['description'] ?? '',
            'url' => $data['url'] ?? config('seo.site_url'),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => config('seo.site_name'),
                'url' => config('seo.site_url'),
            ],
            'inLanguage' => $data['language'] ?? config('seo.language'),
        ], $data);
    }

    /**
     * Build Service Schema
     */
    public static function service(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'provider' => [
                '@type' => 'LocalBusiness',
                'name' => config('seo.site_name'),
            ],
        ];

        if (isset($data['areaServed'])) {
            $schema['areaServed'] = $data['areaServed'];
        }

        if (isset($data['offers'])) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $data['offers']['price'],
                'priceCurrency' => $data['offers']['currency'] ?? 'SAR',
                'availability' => $data['offers']['inStock'] 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
            ];
        }

        if (isset($data['rating'])) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['rating']['value'],
                'reviewCount' => $data['rating']['count'],
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        return array_merge($schema, $data['additional'] ?? []);
    }

    /**
     * Build Product Schema
     */
    public static function product(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $data['price'],
                'priceCurrency' => $data['currency'] ?? 'SAR',
                'availability' => $data['inStock'] ?? true 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/OutOfStock',
            ],
        ];

        if (isset($data['sku'])) {
            $schema['sku'] = $data['sku'];
        }

        if (isset($data['brand'])) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name' => $data['brand'],
            ];
        }

        if (isset($data['rating'])) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['rating']['value'],
                'reviewCount' => $data['rating']['count'],
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        if (isset($data['reviews'])) {
            $schema['review'] = $data['reviews'];
        }

        return $schema;
    }

    /**
     * Build Article Schema
     */
    public static function article(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['headline'],
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'],
                'url' => $data['authorUrl'] ?? null,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('seo.site_name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset(config('seo.logo', 'images/logo.png')),
                ],
            ],
        ];

        if (isset($data['publishedAt'])) {
            $schema['datePublished'] = $data['publishedAt'];
        }

        if (isset($data['modifiedAt'])) {
            $schema['dateModified'] = $data['modifiedAt'];
        }

        return $schema;
    }

    /**
     * Build BlogPosting Schema
     */
    public static function blogPosting(array $data): array
    {
        $schema = self::article($data);
        $schema['@type'] = 'BlogPosting';
        return $schema;
    }

    /**
     * Build NewsArticle Schema
     */
    public static function newsArticle(array $data): array
    {
        $schema = self::article($data);
        $schema['@type'] = 'NewsArticle';
        return $schema;
    }

    /**
     * Build FAQ Schema
     */
    public static function faq(array $faqs): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [],
        ];

        foreach ($faqs as $faq) {
            $schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ];
        }

        return $schema;
    }

    /**
     * Build AggregateRating Schema
     */
    public static function aggregateRating(float $rating, int $count): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'AggregateRating',
            'ratingValue' => round($rating, 1),
            'reviewCount' => $count,
            'bestRating' => '5',
            'worstRating' => '1',
        ];
    }

    /**
     * Build Breadcrumb Schema
     */
    public static function breadcrumb(array $items): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [],
        ];

        foreach ($items as $index => $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ];
        }

        return $schema;
    }

    /**
     * Build ItemList Schema
     */
    public static function itemList(array $items, string $name): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $name,
            'numberOfItems' => count($items),
            'itemListElement' => [],
        ];

        foreach ($items as $index => $item) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'url' => $item['url'] ?? null,
            ];
        }

        return $schema;
    }

    /**
     * Build Event Schema
     */
    public static function event(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $data['name'],
            'startDate' => $data['startDate'],
            'eventStatus' => $data['status'] ?? 'https://schema.org/EventScheduled',
            'eventAttendanceMode' => $data['attendanceMode'] ?? 'https://schema.org/OfflineEventAttendanceMode',
        ];

        if (isset($data['endDate'])) {
            $schema['endDate'] = $data['endDate'];
        }

        if (isset($data['location'])) {
            $schema['location'] = [
                '@type' => 'Place',
                'name' => $data['location'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => config('seo.country'),
                ],
            ];
        }

        if (isset($data['description'])) {
            $schema['description'] = $data['description'];
        }

        if (isset($data['image'])) {
            $schema['image'] = $data['image'];
        }

        if (isset($data['offers'])) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'price' => $data['offers']['price'],
                'priceCurrency' => $data['offers']['currency'] ?? 'SAR',
                'availability' => 'https://schema.org/InStock',
                'url' => $data['offers']['url'] ?? null,
            ];
        }

        return $schema;
    }

    /**
     * Build City Schema
     */
    public static function city(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'City',
            'name' => $data['name'],
        ];

        if (isset($data['description'])) {
            $schema['description'] = $data['description'];
        }

        if (isset($data['latitude']) && isset($data['longitude'])) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
            ];
        }

        return $schema;
    }

    /**
     * Build LocalBusiness Schema
     */
    public static function localBusiness(array $data): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $data['type'] ?? 'LocalBusiness',
            'name' => $data['name'] ?? config('seo.site_name'),
            'image' => $data['image'] ?? asset(config('seo.logo', 'images/logo.png')),
            'telephone' => config('seo.phone'),
            'email' => config('seo.email'),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $data['streetAddress'] ?? null,
                'addressLocality' => $data['city'] ?? null,
                'addressRegion' => $data['region'] ?? null,
                'postalCode' => $data['postalCode'] ?? null,
                'addressCountry' => config('seo.country'),
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
            ],
            'openingHoursSpecification' => $data['openingHours'] ?? null,
            'priceRange' => $data['priceRange'] ?? '$$',
            'servesCuisine' => $data['cuisine'] ?? null,
        ];

        if (isset($data['rating'])) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $data['rating']['value'],
                'reviewCount' => $data['rating']['count'],
            ];
        }

        if (isset($data['sameAs'])) {
            $schema['sameAs'] = $data['sameAs'];
        }

        return array_filter($schema);
    }

    /**
     * Build Review Schema
     */
    public static function review(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Review',
            'itemReviewed' => [
                '@type' => $data['itemType'] ?? 'Product',
                'name' => $data['itemName'],
            ],
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => $data['rating'],
                'bestRating' => '5',
                'worstRating' => '1',
            ],
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'],
            ],
            'datePublished' => $data['datePublished'] ?? now()->toIso8601String(),
            'reviewBody' => $data['body'] ?? null,
        ];
    }

    /**
     * Build Person Schema
     */
    public static function person(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $data['name'],
            'url' => $data['url'] ?? null,
            'image' => $data['image'] ?? null,
            'jobTitle' => $data['jobTitle'] ?? null,
            'worksFor' => [
                '@type' => 'Organization',
                'name' => $data['worksFor'] ?? config('seo.site_name'),
            ],
            'sameAs' => $data['socialProfiles'] ?? [],
        ];
    }

    /**
     * Build Video Schema
     */
    public static function video(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'thumbnailUrl' => $data['thumbnailUrl'] ?? null,
            'uploadDate' => $data['uploadDate'] ?? now()->toIso8601String(),
            'duration' => $data['duration'] ?? null,
            'contentUrl' => $data['contentUrl'] ?? null,
            'embedUrl' => $data['embedUrl'] ?? null,
        ];
    }

    /**
     * Build Recipe Schema
     */
    public static function recipe(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Recipe',
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Person',
                'name' => $data['author'],
            ],
            'datePublished' => $data['datePublished'] ?? now()->toIso8601String(),
            'prepTime' => $data['prepTime'] ?? null,
            'cookTime' => $data['cookTime'] ?? null,
            'totalTime' => $data['totalTime'] ?? null,
            'recipeYield' => $data['recipeYield'] ?? null,
            'recipeIngredient' => $data['ingredients'] ?? [],
            'recipeInstructions' => $data['instructions'] ?? [],
            'nutrition' => [
                '@type' => 'NutritionInformation',
                'calories' => $data['calories'] ?? null,
            ],
        ];
    }

    /**
     * Build JobPosting Schema
     */
    public static function jobPosting(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $data['title'],
            'description' => $data['description'],
            'datePosted' => $data['datePosted'] ?? now()->toIso8601String(),
            'validThrough' => $data['validThrough'] ?? null,
            'employmentType' => $data['employmentType'] ?? 'FULL_TIME',
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $data['organizationName'] ?? config('seo.site_name'),
                'sameAs' => config('seo.site_url'),
            ],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => $data['country'] ?? config('seo.country'),
                    'addressLocality' => $data['city'] ?? null,
                ],
            ],
            'baseSalary' => isset($data['salary']) ? [
                '@type' => 'MonetaryAmount',
                'currency' => $data['salary']['currency'] ?? 'SAR',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => $data['salary']['min'],
                    'maxValue' => $data['salary']['max'] ?? null,
                    'unitText' => 'YEAR',
                ],
            ] : null,
        ];
    }

    /**
     * Build Course Schema
     */
    public static function course(array $data): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'provider' => [
                '@type' => 'Organization',
                'name' => $data['provider'] ?? config('seo.site_name'),
                'sameAs' => config('seo.site_url'),
            ],
            'offers' => [
                '@type' => 'Offer',
                'price' => $data['price'] ?? 0,
                'priceCurrency' => $data['currency'] ?? 'SAR',
                'availability' => 'https://schema.org/InStock',
            ],
        ];
    }
}
