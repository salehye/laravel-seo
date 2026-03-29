<?php

namespace Salehye\Seo\Tests;

use PHPUnit\Framework\TestCase;
use Salehye\Seo\Services\SeoService;
use Salehye\Seo\Services\SchemaBuilder;

class SeoServiceTest extends TestCase
{
    /** @test */
    public function it_can_create_seo_service()
    {
        $seo = SeoService::make();

        $this->assertInstanceOf(SeoService::class, $seo);
    }

    /** @test */
    public function it_can_set_title()
    {
        $seo = SeoService::make()->title('Test Page');
        $data = $seo->generate();

        $this->assertStringContainsString('Test Page', $data['title']);
    }

    /** @test */
    public function it_can_set_description()
    {
        $seo = SeoService::make()
            ->title('Test')
            ->description('This is a test description');

        $data = $seo->generate();

        $this->assertEquals('This is a test description', $data['description']);
    }

    /** @test */
    public function it_can_set_keywords()
    {
        $seo = SeoService::make()
            ->title('Test')
            ->keywords(['keyword1', 'keyword2', 'keyword3']);

        $data = $seo->generate();

        $this->assertStringContainsString('keyword1', $data['keywords']);
    }

    /** @test */
    public function it_can_generate_html()
    {
        $seo = SeoService::make()
            ->title('Test Page')
            ->description('Test Description')
            ->keywords(['test', 'keywords']);

        $html = $seo->toHtml();

        $this->assertStringContainsString('<title>', $html);
        $this->assertStringContainsString('<meta name="description"', $html);
        $this->assertStringContainsString('<meta name="keywords"', $html);
    }

    /** @test */
    public function it_can_add_organization_schema()
    {
        $seo = SeoService::make()
            ->title('Test')
            ->addOrganizationSchema();

        $data = $seo->generate();

        $this->assertNotEmpty($data['structured_data']);
        $this->assertEquals('Organization', $data['structured_data'][0]['@type']);
    }

    /** @test */
    public function it_can_add_breadcrumb_schema()
    {
        $seo = SeoService::make()
            ->title('Test')
            ->addBreadcrumbSchema([
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'Page', 'url' => url('/page')],
            ]);

        $data = $seo->generate();

        $breadcrumb = collect($data['structured_data'])->firstWhere('@type', 'BreadcrumbList');

        $this->assertNotNull($breadcrumb);
        $this->assertEquals(2, count($breadcrumb['itemListElement']));
    }

    /** @test */
    public function it_can_add_faq_schema()
    {
        $seo = SeoService::make()
            ->title('Test')
            ->addFaqSchema([
                ['question' => 'Q1', 'answer' => 'A1'],
                ['question' => 'Q2', 'answer' => 'A2'],
            ]);

        $data = $seo->generate();

        $faq = collect($data['structured_data'])->firstWhere('@type', 'FAQPage');

        $this->assertNotNull($faq);
        $this->assertEquals(2, count($faq['mainEntity']));
    }
}
