<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SEO Routes
|--------------------------------------------------------------------------
|
| Routes for sitemap.xml and robots.txt
|
*/

// Sitemap Route
Route::get('/sitemap.xml', function () {
    $cacheKey = config('seo.cache_prefix', 'seo_').'sitemap';

    if (! config('seo.sitemap.enabled', true)) {
        abort(404);
    }

    $sitemap = Cache::remember($cacheKey, config('seo.cache_ttl', 3600), function () {
        $urls = [];

        // Add static pages from config
        $staticPages = config('seo.sitemap.static_pages', []);
        foreach ($staticPages as $path => $options) {
            $urls[] = [
                'loc' => config('seo.site_url').ltrim($path, '/'),
                'lastmod' => now()->toIso8601String(),
                'changefreq' => $options['frequency'] ?? config('seo.sitemap.frequency', 'daily'),
                'priority' => $options['priority'] ?? config('seo.sitemap.priority', 0.8),
            ];
        }

        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $xml .= '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>'.PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'.PHP_EOL;
        $xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml">'.PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '    <url>'.PHP_EOL;
            $xml .= '        <loc>'.htmlspecialchars($url['loc'], ENT_QUOTES, 'UTF-8').'</loc>'.PHP_EOL;

            if (isset($url['lastmod'])) {
                $xml .= '        <lastmod>'.$url['lastmod'].'</lastmod>'.PHP_EOL;
            }

            if (isset($url['changefreq'])) {
                $xml .= '        <changefreq>'.$url['changefreq'].'</changefreq>'.PHP_EOL;
            }

            if (isset($url['priority'])) {
                $xml .= '        <priority>'.number_format($url['priority'], 1).'</priority>'.PHP_EOL;
            }

            $xml .= '    </url>'.PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    });

    return response($sitemap, 200, [
        'Content-Type' => 'text/xml',
    ]);
})->name('seo.sitemap');

// Robots.txt Route
Route::get('/robots.txt', function () {
    $cacheKey = config('seo.cache_prefix', 'seo_').'robots';

    $robots = Cache::remember($cacheKey, config('seo.cache_ttl', 3600), function () {
        $lines = [
            'User-agent: *',
        ];

        // Add disallow rules from config
        $disallow = config('seo.robots_disallow', []);
        foreach ($disallow as $path) {
            $lines[] = 'Disallow: '.$path;
        }

        // Add sitemap location
        $lines[] = '';
        $lines[] = 'Sitemap: '.config('seo.site_url').'/sitemap.xml';

        // Add host if needed
        // $lines[] = 'Host: ' . config('seo.site_url');

        return implode(PHP_EOL, $lines);
    });

    return response($robots, 200, [
        'Content-Type' => 'text/plain',
    ]);
})->name('seo.robots');

// Sitemap XSL (for pretty viewing in browser)
Route::get('/sitemap.xsl', function () {
    $xsl = <<<'XSL'
<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>Sitemap</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <style type="text/css">
                    body { font-family: Arial, sans-serif; font-size: 13px; color: #333; }
                    table { border: none; border-collapse: collapse; width: 100%; }
                    th { background-color: #eee; text-align: left; padding: 5px; border-bottom: 1px solid #ccc; }
                    td { padding: 5px; border-bottom: 1px solid #eee; }
                    tr:hover { background-color: #f5f5f5; }
                    a { color: #0066cc; text-decoration: none; }
                    a:hover { text-decoration: underline; }
                </style>
            </head>
            <body>
                <h1>Sitemap</h1>
                <p>Total URLs: <xsl:value-of select="count(sitemap:urlset/sitemap:url)"/></p>
                <table>
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th>Last Modified</th>
                            <th>Change Frequency</th>
                            <th>Priority</th>
                        </tr>
                    </thead>
                    <tbody>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                            <tr>
                                <td><a href="{sitemap:loc}"><xsl:value-of select="sitemap:loc"/></a></td>
                                <td><xsl:value-of select="sitemap:lastmod"/></td>
                                <td><xsl:value-of select="sitemap:changefreq"/></td>
                                <td><xsl:value-of select="sitemap:priority"/></td>
                            </tr>
                        </xsl:for-each>
                    </tbody>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
XSL;

    return response($xsl, 200, [
        'Content-Type' => 'application/xslt+xml',
    ]);
})->name('seo.sitemap.xsl');
