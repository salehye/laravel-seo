<?php

namespace Salehye\Seo\Tests;

use Salehye\Seo\Services\SeoService;

class HelpersTest extends TestCase
{
    /** @test */
    public function it_can_get_seo_singleton()
    {
        $this->assertInstanceOf(SeoService::class, seo());
        $this->assertSame(seo(), seo());
    }

    /** @test */
    public function it_can_set_and_get_title_via_helper()
    {
        seo_title('Helper Title');

        $this->assertEquals('Helper Title | Test Site', seo_title());
        $this->assertStringContainsString('<title>Helper Title | Test Site</title>', seo_render());
    }

    /** @test */
    public function it_can_set_and_get_description_via_helper()
    {
        seo_description('Helper Description');

        $this->assertEquals('Helper Description', seo_description());
        $this->assertStringContainsString('content="Helper Description"', seo_render());
    }

    /** @test */
    public function it_can_add_schema_via_helper()
    {
        seo_organization_schema(['name' => 'Test Org']);

        $html = seo_render();
        $this->assertStringContainsString('"@type": "Organization"', $html);
        $this->assertStringContainsString('"name": "Test Org"', $html);
    }

    /** @test */
    public function it_can_chain_helpers()
    {
        seo_title('Chained')->description('Description');

        $this->assertEquals('Chained | Test Site', seo_title());
        $this->assertEquals('Description', seo_description());
    }
}
