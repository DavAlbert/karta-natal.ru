<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\DailyHoroscope;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://natalscope.com'), '/');
        $locales = config('app.available_locales', ['en']);
        $signs = DailyHoroscope::SIGNS;

        $urls = [];

        // Homepage + horoscope hub for each locale
        foreach ($locales as $loc) {
            $prefix = $loc === 'en' ? '' : '/' . $loc;
            $urls[] = [
                'loc' => $baseUrl . $prefix . '/',
                'changefreq' => 'daily',
                'priority' => '1.0',
            ];
            $urls[] = [
                'loc' => $baseUrl . $prefix . '/horoscope',
                'changefreq' => 'daily',
                'priority' => '0.9',
            ];
        }

        // Sign pages (one per sign per locale, no date in URL)
        foreach ($locales as $loc) {
            $prefix = $loc === 'en' ? '' : '/' . $loc;
            foreach ($signs as $sign) {
                $urls[] = [
                    'loc' => $baseUrl . $prefix . '/horoscope/' . $sign,
                    'changefreq' => 'daily',
                    'priority' => '0.9',
                ];
            }
        }

        // Blog index per locale
        foreach ($locales as $loc) {
            $prefix = $loc === 'en' ? '' : '/' . $loc;
            $urls[] = [
                'loc' => $baseUrl . $prefix . '/blog',
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        // Blog posts
        $posts = BlogPost::published()->get();
        foreach ($posts as $post) {
            $prefix = $post->locale === 'en' ? '' : '/' . $post->locale;
            $urls[] = [
                'loc' => $baseUrl . $prefix . '/blog/' . $post->slug,
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'lastmod' => $post->updated_at->toW3cString(),
            ];
        }

        $xml = $this->buildSitemapXml($urls);

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    private function buildSitemapXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $u) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc']) . '</loc>' . "\n";
            $xml .= '    <changefreq>' . ($u['changefreq'] ?? 'weekly') . '</changefreq>' . "\n";
            $xml .= '    <priority>' . ($u['priority'] ?? '0.5') . '</priority>' . "\n";
            if (!empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . '</lastmod>' . "\n";
            }
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
