<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('common.dashboard_title') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Daily Horoscope Card --}}
            @if(isset($horoscope) && $horoscope && $sunSignKey)
            @php
                $content = $horoscope->content ?? [];
                $scores = $content['scores'] ?? ['overall' => 75, 'love' => 70, 'career' => 80, 'health' => 75];
                $signName = __('astrology.sign_' . $sunSignKey);
                $formattedDate = now()->locale(app()->getLocale())->translatedFormat('j F');
                $tomorrow = now()->addDay()->locale(app()->getLocale())->translatedFormat('j F');
            @endphp
            <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 overflow-hidden shadow-sm sm:rounded-xl border border-indigo-100">
                <div class="p-5 sm:p-6">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                                <img src="/images/zodiac/{{ $sunSignKey }}.webp" alt="{{ $signName }}" class="w-8 h-8 object-contain">
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ __('common.daily_horoscope_title') }}</h3>
                                <p class="text-xs text-indigo-500 font-medium">{{ $signName }} &middot; {{ $formattedDate }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-black text-indigo-600">{{ $scores['overall'] }}<span class="text-lg text-indigo-400">%</span></div>
                            <div class="text-[10px] text-indigo-400 uppercase tracking-wider font-semibold">{{ __('common.daily_score') }}</div>
                        </div>
                    </div>

                    {{-- Overview --}}
                    @if(!empty($content['overview']))
                    <p class="text-sm text-gray-700 leading-relaxed mb-4">{{ Str::limit($content['overview'], 280) }}</p>
                    @endif

                    {{-- Score bars --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-4">
                        <div class="bg-white/60 rounded-lg p-2.5 border border-pink-100">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-gray-500">❤️ {{ __('common.daily_love') }}</span>
                                <span class="text-xs font-bold text-pink-500">{{ $scores['love'] }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-pink-400 to-rose-500 rounded-full" style="width: {{ $scores['love'] }}%"></div>
                            </div>
                        </div>
                        <div class="bg-white/60 rounded-lg p-2.5 border border-amber-100">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-gray-500">💼 {{ __('common.daily_career') }}</span>
                                <span class="text-xs font-bold text-amber-500">{{ $scores['career'] }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full" style="width: {{ $scores['career'] }}%"></div>
                            </div>
                        </div>
                        <div class="bg-white/60 rounded-lg p-2.5 border border-emerald-100">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-gray-500">🏥 {{ __('common.daily_health') }}</span>
                                <span class="text-xs font-bold text-emerald-500">{{ $scores['health'] }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full" style="width: {{ $scores['health'] }}%"></div>
                            </div>
                        </div>
                        <div class="bg-white/60 rounded-lg p-2.5 border border-violet-100">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="text-xs text-gray-500">🍀 {{ __('common.daily_luck') }}</span>
                                <span class="text-xs font-bold text-violet-500">{{ $scores['luck'] ?? 65 }}%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-violet-400 to-purple-500 rounded-full" style="width: {{ $scores['luck'] ?? 65 }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer: tomorrow teaser + link --}}
                    <div class="flex items-center justify-between pt-3 border-t border-indigo-100/50">
                        <div class="flex items-center gap-2 text-xs text-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('common.daily_tomorrow_hint', ['date' => $tomorrow]) }}
                        </div>
                        <a href="{{ locale_route('horoscope.sign', ['sign' => $sunSignKey]) }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                            {{ __('common.daily_read_more') }} &rarr;
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- Charts --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($charts->isEmpty())
                        <div class="text-center py-10">
                            <h3 class="text-lg font-medium text-gray-900">{{ __('common.dashboard_empty_title') }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('common.dashboard_empty_text') }}</p>
                            <div class="mt-6">
                                <a href="{{ locale_route('welcome') }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('common.dashboard_create_btn') }}
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($charts as $chart)
                                <a href="{{ locale_route('charts.show', ['natalChart' => $chart]) }}" class="block border rounded-lg p-6 hover:shadow-lg hover:border-indigo-200 transition-all">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">{{ $chart->name }}</h3>
                                            <p class="text-xs text-gray-500">{{ $chart->created_at->format('d.m.Y H:i') }}</p>
                                        </div>
                                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                            {{ ucfirst($chart->type) }}
                                        </span>
                                    </div>

                                    <div class="space-y-2 text-sm text-gray-600 mb-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $chart->birth_date->format('d.m.Y') }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $chart->birth_time ? $chart->birth_time->format('H:i') : __('common.time_unknown') }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $chart->birth_place }}
                                        </div>
                                    </div>

                                    <div class="border-t pt-4">
                                        <span class="w-full text-center text-indigo-600 font-medium hover:text-indigo-800 text-sm">
                                            {{ __('common.dashboard_open_btn') }} &rarr;
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
