<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Privacy Policy | NatalScope</title>
    <meta name="description" content="Privacy Policy for NatalScope service. Learn how we collect, use, and protect your personal data.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/') }}/privacy">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            background-color: #0B1120;
            color: #e2e8f0;
            overflow-x: hidden;
            max-width: 100%;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 border-b border-indigo-900/30 bg-[#0B1120]/95 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ locale_route('welcome') }}" class="flex items-center gap-3">
                    <span class="text-xl font-bold text-white tracking-tight">
                        NATAL<span class="text-indigo-400">SCOPE</span>
                    </span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="pt-28 pb-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-8">Privacy Policy</h1>

            <div class="prose prose-invert prose-indigo max-w-none space-y-8 text-indigo-200">
                <p class="text-indigo-300">
                    Effective Date: {{ date('F j, Y') }}
                </p>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">1. Introduction</h2>
                    <p>
                        This Privacy Policy describes how SMART CREATOR AI LLC ("we", "us", or "our") collects, uses, and protects the personal data of users of the NatalScope service ("Service").
                    </p>
                    <p>
                        By using our Service, you agree to the terms of this Privacy Policy. If you do not agree with these terms, please do not use the Service.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">2. Information We Collect</h2>
                    <p>To provide our natal chart calculation services, we collect the following information:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li><strong class="text-white">Name</strong> - to personalize your chart results</li>
                        <li><strong class="text-white">Email Address</strong> - for sending results and authentication</li>
                        <li><strong class="text-white">Date of Birth</strong> - required for astrological calculations</li>
                        <li><strong class="text-white">Time of Birth</strong> - required for accurate ascendant and house calculations</li>
                        <li><strong class="text-white">Place of Birth</strong> - required for planetary position calculations</li>
                        <li><strong class="text-white">Gender</strong> - for personalized interpretations</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">3. How We Use Your Information</h2>
                    <p>We use the collected information exclusively for:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Calculating your natal chart</li>
                        <li>Generating personalized astrological interpretations</li>
                        <li>Sending calculation results to your email</li>
                        <li>Authentication and access to your account</li>
                        <li>Sending informational messages (with your consent)</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">4. Data Storage and Security</h2>
                    <p>
                        We implement appropriate organizational and technical measures to protect personal data from unauthorized access, destruction, modification, blocking, copying, and distribution.
                    </p>
                    <p class="mt-4">
                        Data is stored on secure servers. Access to personal data is limited to authorized personnel who are required to maintain confidentiality.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">5. Sharing Information with Third Parties</h2>
                    <p>
                        We do not sell, trade, or transfer your personal data to third parties without your consent, except in the following cases:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Legal requirements</li>
                        <li>Protection of our legitimate rights and interests</li>
                        <li>Use of data processing services (e.g., email services), provided they maintain confidentiality</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">6. Cookies and Analytics</h2>
                    <p>
                        Our Service uses cookies to ensure proper functionality, user authentication, and service quality improvement. You can disable cookies in your browser settings, but this may affect the functionality of the Service.
                    </p>
                    <p class="mt-4">
                        We use the following analytics services to understand how users interact with our Service:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li><strong class="text-white">Google Analytics</strong> - a web analytics service provided by Google LLC. Google Analytics uses cookies to collect information about your use of the Service, including your IP address, browser type, pages visited, and time spent on pages. This information is used to analyze website traffic and improve user experience. For more information, see <a href="https://policies.google.com/privacy" class="text-indigo-400 hover:text-white" target="_blank" rel="noopener">Google's Privacy Policy</a>.</li>
                        <li><strong class="text-white">Yandex Metrica</strong> - a web analytics service provided by Yandex LLC. Yandex Metrica collects data about your visits, including pages viewed, session duration, and user behavior through session replay technology (Webvisor). For more information, see <a href="https://yandex.com/legal/privacy/" class="text-indigo-400 hover:text-white" target="_blank" rel="noopener">Yandex's Privacy Policy</a>.</li>
                    </ul>
                    <p class="mt-4">
                        You can opt out of Google Analytics by installing the <a href="https://tools.google.com/dlpage/gaoptout" class="text-indigo-400 hover:text-white" target="_blank" rel="noopener">Google Analytics Opt-out Browser Add-on</a>.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">7. Your Rights</h2>
                    <p>You have the right to:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Request information about the personal data we process</li>
                        <li>Request correction of inaccurate data</li>
                        <li>Request deletion of your data</li>
                        <li>Withdraw consent to data processing</li>
                    </ul>
                    <p class="mt-4">
                        To exercise your rights, please contact us via email.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">8. Changes to This Policy</h2>
                    <p>
                        We reserve the right to modify this Privacy Policy at any time. The current version is always available on this page. By continuing to use the Service after changes are made, you agree to the updated Policy.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">9. Contact Information</h2>
                    <p>
                        For any questions regarding personal data processing, please contact:
                    </p>
                    <p class="mt-4">
                        <strong class="text-white">SMART CREATOR AI LLC</strong><br>
                        Website: <a href="{{ config('app.url', 'https://natalscope.com') }}" class="text-indigo-400 hover:text-white">natalscope.com</a>
                    </p>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#050914] border-t border-indigo-900/20 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-8">
                <a href="{{ locale_route('welcome') }}" class="text-xl font-bold text-white/40 tracking-tight">
                    NATAL<span class="text-indigo-400/40">SCOPE</span>
                </a>
                <nav class="flex flex-wrap justify-center gap-6 text-sm">
                    <a href="{{ url('/privacy') }}" class="text-white font-medium">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" class="text-indigo-400 hover:text-white transition-colors">Terms of Service</a>
                </nav>
            </div>
            <div class="border-t border-indigo-900/20 pt-6 text-center">
                <p class="text-indigo-600 text-xs">
                    &copy; {{ date('Y') }} SMART CREATOR AI LLC. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>

</html>
