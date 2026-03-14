<form id="{{ $formId }}" class="space-y-5" method="POST">
    @csrf
    <input type="hidden" name="locale" value="{{ app()->getLocale() }}">
    <input type="hidden" name="purpose" value="general">
    <input type="hidden" id="{{ $prefix }}birth_date" name="birth_date">
    <input type="hidden" id="{{ $prefix }}birth_time" name="birth_time">
    <input type="hidden" id="{{ $prefix }}city_id" name="city_id" required>

    <div>
        <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
            <i class="fas fa-user mr-1 text-indigo-500"></i>{{ __('common.form_name') }}
        </label>
        <input type="text" name="name" required class="w-full input-pro rounded-lg px-4 py-3" placeholder="{{ __('common.form_name_placeholder') }}">
    </div>

    <div>
        <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
            <i class="fas fa-envelope mr-1 text-indigo-500"></i>{{ __('common.form_email') }}
        </label>
        <input type="email" name="email" required autocomplete="email" class="w-full input-pro rounded-lg px-4 py-3" placeholder="{{ __('common.form_email_placeholder') }}">
    </div>

    <div>
        <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
            <i class="fas fa-venus-mars mr-1 text-indigo-500"></i>{{ __('common.form_gender') }}
        </label>
        <div class="grid grid-cols-2 gap-3">
            <label class="gender-btn cursor-pointer">
                <input type="radio" name="gender" value="male" required class="hidden" checked>
                <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border border-indigo-800/50 bg-[#0f172a]/60 hover:border-indigo-600/50 transition-all">
                    <i class="fas fa-mars text-indigo-400"></i>
                    <span class="text-white text-sm">{{ __('common.form_gender_male') }}</span>
                </div>
            </label>
            <label class="gender-btn cursor-pointer">
                <input type="radio" name="gender" value="female" required class="hidden">
                <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-lg border border-indigo-800/50 bg-[#0f172a]/60 hover:border-indigo-600/50 transition-all">
                    <i class="fas fa-venus text-indigo-400"></i>
                    <span class="text-white text-sm">{{ __('common.form_gender_female') }}</span>
                </div>
            </label>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
            <i class="far fa-calendar-alt mr-1 text-indigo-500"></i>{{ __('common.form_birth_date') }}
        </label>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
            <select id="{{ $prefix }}birth_day" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer order-2 sm:order-1">
                <option value="">{{ __('common.form_day') }}</option>
            </select>
            <select id="{{ $prefix }}birth_month" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer col-span-2 sm:col-span-1 order-1 sm:order-2">
                <option value="">{{ __('common.form_month') }}</option>
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ __('common.month_' . str_pad($m, 2, '0', STR_PAD_LEFT)) }}</option>
                @endfor
            </select>
            <select id="{{ $prefix }}birth_year" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer order-3">
                <option value="">{{ __('common.form_year') }}</option>
            </select>
        </div>
    </div>

    <div>
        <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
            <i class="far fa-clock mr-1 text-indigo-500"></i>{{ __('common.form_birth_time') }}
        </label>
        <div class="grid grid-cols-2 gap-2">
            <select id="{{ $prefix }}birth_hour" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer">
                <option value="">{{ __('common.form_hour') }}</option>
            </select>
            <select id="{{ $prefix }}birth_minute" required class="w-full input-pro rounded-lg px-3 py-3 text-center appearance-none cursor-pointer">
                <option value="">{{ __('common.form_minute') }}</option>
            </select>
        </div>
        <label class="flex items-center gap-2 mt-2 cursor-pointer group">
            <input type="checkbox" id="{{ $prefix }}time_unknown"
                class="w-4 h-4 text-indigo-600 bg-[#0f172a] border-indigo-800 rounded focus:ring-indigo-500 focus:ring-2">
            <span class="text-xs text-indigo-400/80 group-hover:text-indigo-300 transition-colors">{{ __('common.form_time_unknown') }}</span>
        </label>
    </div>

    <div>
        <label class="block text-xs font-semibold text-indigo-300/80 uppercase tracking-wider mb-1.5">
            <i class="fas fa-city mr-1 text-indigo-500"></i>{{ __('common.form_city') }}
        </label>
        <div class="relative">
            <input type="text" id="{{ $prefix }}birth_place_search" autocomplete="off"
                class="w-full input-pro rounded-lg px-4 py-3 pr-10" placeholder="{{ __('common.form_city_placeholder') }}">
            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-indigo-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <div id="{{ $prefix }}cities_dropdown" class="hidden absolute z-50 mt-1 bg-[#1e293b] border border-indigo-500/30 rounded-lg shadow-xl max-h-60 overflow-y-auto w-full"></div>
        </div>
        <p class="text-xs text-amber-400/80 mt-1.5 flex items-center gap-1.5">
            <i class="fas fa-language"></i>
            {{ __('common.form_city_hint') }}
        </p>
        <p id="{{ $prefix }}city_details" class="hidden text-xs text-indigo-300 mt-1.5"></p>
    </div>

    <div>
        <label class="flex items-start gap-3 cursor-pointer group">
            <input type="checkbox" name="marketing_consent" value="1"
                class="mt-1 w-4 h-4 text-indigo-600 bg-[#0f172a] border-indigo-800 rounded focus:ring-indigo-500 focus:ring-2">
            <span class="text-xs text-indigo-300/70 leading-relaxed group-hover:text-indigo-200 transition-colors">
                {{ __('common.form_marketing_consent') }}
            </span>
        </label>
    </div>

    <div id="{{ $prefix }}turnstile" class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-size="invisible" data-callback="onTurnstile_{{ $prefix }}"></div>

    <button type="submit" disabled
        class="w-full mt-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none text-base sm:text-lg">
        {{ __('common.form_submit') }}
    </button>

    <p class="text-xs text-center text-indigo-400/40 mt-3">
        {{ __('common.form_privacy_note') }}
    </p>
</form>
