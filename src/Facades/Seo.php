<?php

namespace Salehye\Seo\Facades;

use Illuminate\Support\Facades\Facade;
use Salehye\Seo\Services\SeoService;

/**
 * @method static static make()
 * @method static static title(string $title, ?string $suffix = null)
 * @method static static description(string $description, int $limit = 160)
 * @method static static keywords(string|array $keywords)
 * @method static static type(string $type)
 * @method static static image(string $url, ?string $alt = null, ?int $width = null, ?int $height = null)
 * @method static static canonical(string $url)
 * @method static static robots(string $robots)
 * @method static static addMeta(string $name, string $content, string $type = 'name')
 * @method static static addOrganizationSchema(array $data = [])
 * @method static static addWebsiteSchema(array $data = [])
 * @method static static addWebPageSchema(array $data)
 * @method static static addLocalBusinessSchema(array $data)
 * @method static static addServiceSchema(array $data)
 * @method static static addProductSchema(array $data)
 * @method static static addArticleSchema(array $data)
 * @method static static addBlogPostingSchema(array $data)
 * @method static static addNewsArticleSchema(array $data)
 * @method static static addFaqSchema(array $faqs)
 * @method static static addAggregateRatingSchema(float $rating, int $count)
 * @method static static addReviewSchema(array $data)
 * @method static static addBreadcrumbSchema(array $items)
 * @method static static addItemListSchema(array $items, string $name)
 * @method static static addEventSchema(array $data)
 * @method static static addCitySchema(array $data)
 * @method static static addPersonSchema(array $data)
 * @method static static addVideoSchema(array $data)
 * @method static static addRecipeSchema(array $data)
 * @method static static addJobPostingSchema(array $data)
 * @method static static addCourseSchema(array $data)
 * @method static static addStructuredData(array $data, ?string $key = null)
 * @method static static addOpenGraph(array $data)
 * @method static static addTwitterCard(array $data)
 * @method static static array generate()
 * @method static static string toHtml()
 * @method static static array forInertia()
 * @method static static array forBlade()
 * @method static static \Salehye\Seo\Services\SeoTagBuilder getTagBuilder()
 * @method static static clear()
 *
 * @see SeoService
 */
class Seo extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'seo';
    }
}
