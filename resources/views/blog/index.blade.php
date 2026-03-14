<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth" style="overflow-x: hidden;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">

    @php
        $currentLocale = app()->getLocale();
        $baseUrl = rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/');
        $localePath = $currentLocale === 'en' ? '' : '/' . $currentLocale;
        $canonicalUrl = $baseUrl . $localePath . '/blog';
        $locales = config('app.available_locales', ['en']);
    @endphp

    <title>{{ __('blog.seo_title') }} | NatalScope</title>
    <meta name="description" content="{{ __('blog.seo_description') }}">
    <meta name="keywords" content="{{ __('blog.seo_keywords') }}">
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    @foreach($locales as $loc)
    <link rel="alternate" hreflang="{{ $loc }}" href="{{ $baseUrl }}{{ $loc === 'en' ? '' : '/' . $loc }}/blog">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $baseUrl }}/blog">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('blog.seo_title') }} | NatalScope">
    <meta property="og:description" content="{{ __('blog.seo_description') }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:locale" content="{{ $currentLocale }}">
    <meta property="og:site_name" content="NatalScope">

    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Blog",
        "name": "NatalScope — {{ __('blog.title') }}",
        "url": "{{ $canonicalUrl }}",
        "description": "{{ __('blog.seo_description') }}",
        "inLanguage": "{{ $currentLocale }}"
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@7.1.0/js/all.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { background-color: #0B1120; color: #e2e8f0; overflow-x: hidden; max-width: 100%; }
        .constellation-bg {
            background-image:
                radial-gradient(1px 1px at 10% 20%, rgba(255,255,255,0.15) 0%, transparent 50%),
                radial-gradient(1px 1px at 30% 60%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(1px 1px at 50% 30%, rgba(255,255,255,0.12) 0%, transparent 50%),
                radial-gradient(1px 1px at 70% 80%, rgba(255,255,255,0.08) 0%, transparent 50%),
                radial-gradient(1px 1px at 90% 10%, rgba(255,255,255,0.1) 0%, transparent 50%);
        }
    </style>
</head>
<body class="font-sans antialiased">
    {{-- Navigation --}}
    <nav class="fixed top-0 w-full z-50 bg-[#0B1120]/80 backdrop-blur-sm border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ locale_route('welcome') }}" class="text-lg font-bold text-white">NatalScope</a>
                <div class="flex items-center space-x-6">
                    <a href="{{ locale_route('blog.index') }}" class="text-sm text-indigo-400 font-medium">{{ __('blog.blog') }}</a>
                    <a href="{{ locale_route('horoscope.index') }}" class="text-sm text-gray-300 hover:text-white">{{ __('blog.horoscope') }}</a>
                    <a href="{{ locale_route('welcome') }}#calcForm" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-indigo-500 hover:to-purple-500 transition-all">{{ __('blog.get_chart') }}</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-16">
        {{-- Hero --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('blog.title') }}</h1>
            <p class="text-lg text-gray-400 max-w-2xl">{{ __('blog.subtitle') }}</p>
        </div>

        {{-- Posts grid --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                <a href="{{ locale_route('blog.show', ['slug' => $post->slug]) }}" class="group block bg-[#111827] rounded-xl overflow-hidden border border-white/5 hover:border-indigo-500/30 transition-all duration-300 hover:transform hover:-translate-y-1">
                    @if($post->banner)
                    <div class="aspect-video overflow-hidden">
                        <img src="{{ Storage::url($post->banner) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                    </div>
                    @else
                    <div class="aspect-video bg-gradient-to-br from-indigo-900/50 to-purple-900/50 constellation-bg flex items-center justify-center">
                        <i class="fa-solid fa-star text-4xl text-indigo-500/30"></i>
                    </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center space-x-2 mb-3">
                            <span class="text-xs text-indigo-400">{{ $post->published_at?->format('M d, Y') }}</span>
                        </div>
                        <h2 class="text-lg font-semibold text-white group-hover:text-indigo-300 transition-colors mb-2">{{ $post->title }}</h2>
                        @if($post->excerpt)
                        <p class="text-sm text-gray-400 line-clamp-3">{{ $post->excerpt }}</p>
                        @endif
                        <div class="mt-4 text-sm text-indigo-400 group-hover:text-indigo-300 font-medium">
                            {{ __('blog.read_more') }} <i class="fa-solid fa-arrow-right text-xs ml-1 group-hover:translate-x-1 transition-transform inline-block"></i>
                        </div>
                    </div>
                </a>
                @empty
                <div class="col-span-full text-center py-16">
                    <i class="fa-solid fa-feather-pointed text-5xl text-gray-600 mb-4 block"></i>
                    <p class="text-gray-400 text-lg">{{ __('blog.no_posts') }}</p>
                </div>
                @endforelse
            </div>

            @if($posts->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $posts->links() }}
            </div>
            @endif
        </div>

        {{-- CTA Section --}}
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
            <div class="rounded-2xl bg-gradient-to-r from-indigo-900/50 to-purple-900/50 border border-indigo-500/20 p-8 md:p-12 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ __('blog.cta_title') }}</h2>
                <p class="text-gray-300 mb-6 max-w-xl mx-auto">{{ __('blog.cta_text') }}</p>
                <a href="{{ locale_route('welcome') }}#calcForm" class="inline-block px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg shadow-indigo-500/25">
                    {{ __('blog.cta_button') }}
                </a>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="border-t border-white/5 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500">
            <div>&copy; {{ date('Y') }} NatalScope</div>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="{{ locale_route('welcome') }}" class="hover:text-gray-300">{{ __('blog.home') }}</a>
                <a href="{{ locale_route('blog.index') }}" class="hover:text-gray-300">{{ __('blog.blog') }}</a>
                <a href="{{ route('privacy') }}" class="hover:text-gray-300">{{ __('blog.privacy') }}</a>
                <a href="{{ route('terms') }}" class="hover:text-gray-300">{{ __('blog.terms') }}</a>
            </div>
        </div>
    </footer>
</body>
</html>
