<?php

namespace App\Http\Controllers;

use App\Models\DailyHoroscope;
use Illuminate\Support\Facades\App;
use App\Services\DailyTransitService;
use App\Services\HoroscopeTemplateService;

class HoroscopeController extends Controller
{
    public function __construct(
        protected DailyTransitService $transit,
        protected HoroscopeTemplateService $templates
    ) {}

    public function index()
    {
        $locale = App::getLocale();
        $signs = DailyHoroscope::SIGNS;

        $horoscopes = DailyHoroscope::where('locale', $locale)
            ->get()
            ->keyBy('sign');

        return view('horoscope.index', compact('signs', 'horoscopes'));
    }

    public function show(string $sign)
    {
        $sign = strtolower($sign);
        if (!DailyHoroscope::isValidSign($sign)) {
            abort(404);
        }

        $locale = App::getLocale();
        $today = now()->format('Y-m-d');
        $horoscope = DailyHoroscope::where('sign', $sign)->where('locale', $locale)->first();

        if (!$horoscope) {
            $transitData = $this->transit->getTransitsForDate($today);
            $content = $this->templates->generate($transitData, $sign, $today, $locale);
            $horoscope = DailyHoroscope::create([
                'sign' => $sign,
                'locale' => $locale,
                'transit_data' => $transitData,
                'content' => $content,
            ]);
        }

        $signName = __('astrology.sign_' . $sign);

        return view('horoscope.show', compact('horoscope', 'sign', 'signName'));
    }
}
