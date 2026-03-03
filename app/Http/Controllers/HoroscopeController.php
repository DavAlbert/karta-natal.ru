<?php

namespace App\Http\Controllers;

use App\Models\DailyHoroscope;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
        $today = now()->format('Y-m-d');
        $locale = App::getLocale();
        $signs = DailyHoroscope::SIGNS;

        $horoscopes = DailyHoroscope::where('date', $today)
            ->where('locale', $locale)
            ->get()
            ->keyBy('sign');

        return view('horoscope.index', compact('signs', 'horoscopes', 'today'));
    }

    public function show(Request $request, string $sign, ?string $date = null)
    {
        $sign = strtolower($sign);
        if (!DailyHoroscope::isValidSign($sign)) {
            abort(404);
        }

        if ($date) {
            try {
                $dateObj = Carbon::createFromFormat('Y-m-d', $date);
            } catch (\Exception $e) {
                abort(404);
            }
            $displayDate = $date;
        } else {
            $dateObj = now();
            $displayDate = $dateObj->format('Y-m-d');
        }

        $locale = App::getLocale();
        $horoscope = DailyHoroscope::where('date', $displayDate)->where('sign', $sign)->where('locale', $locale)->first();

        if (!$horoscope) {
            $transitData = $this->transit->getTransitsForDate($displayDate);
            $content = $this->templates->generate($transitData, $sign, $displayDate, $locale);
            $horoscope = DailyHoroscope::create([
                'date' => $displayDate,
                'sign' => $sign,
                'locale' => $locale,
                'transit_data' => $transitData,
                'content' => $content,
            ]);
        }

        $signName = __('astrology.sign_' . $sign);
        $today = now()->format('Y-m-d');
        $isToday = $displayDate === $today;

        return view('horoscope.show', compact('horoscope', 'sign', 'signName', 'displayDate', 'today', 'isToday'));
    }
}
