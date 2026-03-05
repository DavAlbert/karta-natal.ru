<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ locale_route('welcome') }}" class="text-xl font-bold text-gray-800 tracking-tight">
                        NATAL<span class="text-indigo-400">SCOPE</span>
                    </a>
                </div>
            </div>

            <!-- Language Switcher + Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 sm:gap-3">
                @php
                    $currentLocale = app()->getLocale();
                    $locales = config('app.available_locales', ['en']);
                    $langFlags = ['en' => '🇬🇧', 'ru' => '🇷🇺', 'es' => '🇪🇸', 'fr' => '🇫🇷', 'pt' => '🇧🇷', 'hi' => '🇮🇳'];
                    $baseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
                @endphp
                <div x-data="{ langOpen: false }" class="relative">
                    <button @click="langOpen = !langOpen" @click.outside="langOpen = false" class="inline-flex items-center gap-1 px-2 py-2 text-sm text-gray-500 hover:text-gray-700 transition">
                        <span>{{ $langFlags[$currentLocale] ?? '🌐' }}</span>
                        <span class="uppercase font-medium text-xs">{{ $currentLocale }}</span>
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="langOpen" x-transition class="absolute right-0 mt-1 w-40 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                        @foreach($locales as $loc)
                        <a href="{{ $baseUrl }}{{ $loc === 'en' ? '/' : '/' . $loc . '/' }}"
                           class="flex items-center gap-2 px-3 py-2 text-sm {{ $loc === $currentLocale ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span>{{ $langFlags[$loc] ?? '🌐' }}</span>
                            {{ __('common.lang_' . $loc) }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Language Switcher (mobile) -->
                <div class="px-4 py-2">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">{{ __('common.language') }}</div>
                    <div class="flex flex-wrap gap-1">
                        @foreach(config('app.available_locales', ['en']) as $loc)
                        @php $locBaseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/'); @endphp
                        <a href="{{ $locBaseUrl }}{{ $loc === 'en' ? '/' : '/' . $loc . '/' }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded text-xs {{ $loc === app()->getLocale() ? 'bg-indigo-100 text-indigo-700 font-medium' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            <span>{{ (['en' => '🇬🇧', 'ru' => '🇷🇺', 'es' => '🇪🇸', 'fr' => '🇫🇷', 'pt' => '🇧🇷', 'hi' => '🇮🇳'])[$loc] ?? '🌐' }}</span>
                            {{ strtoupper($loc) }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
