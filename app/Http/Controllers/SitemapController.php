<?php

namespace App\Http\Controllers;

use App\Models\DailyHoroscope;
use Carbon\Carbon;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(config('app.url', 'https://natalscope.com'), '/');
        $locales = config('app.available_locales', ['en']);
        $signs = DailyHoroscope::SIGNS;
        $today = now()->format('Y-m-d');

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

        // Today's sign pages (no date = today)
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

        // Sign + date for every day: 180 days past + 14 days future (SEO: "cancer horoscope march 3" etc.)
        $daysBack = 180;
        $daysAhead = 14;
        for ($i = -$daysAhead; $i <= $daysBack; $i++) {
            $date = Carbon::today()->addDays($i)->format('Y-m-d');
            foreach ($locales as $loc) {
                $prefix = $loc === 'en' ? '' : '/' . $loc;
                foreach ($signs as $sign) {
                    $urls[] = [
                        'loc' => $baseUrl . $prefix . '/horoscope/' . $sign . '/' . $date,
                        'changefreq' => $i >= 0 && $i <= 1 ? 'daily' : 'weekly',
                        'priority' => $i === 0 ? '0.9' : '0.8',
                    ];
                }
            }
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
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }
}
