<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Terms of Service | NatalScope</title>
    <meta name="description" content="Terms of Service for NatalScope online natal chart calculation service.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ rtrim(config('app.url', request()->getSchemeAndHttpHost()), '/') }}/terms">

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
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-8">Terms of Service</h1>

            <div class="prose prose-invert prose-indigo max-w-none space-y-8 text-indigo-200">
                <p class="text-indigo-300">
                    Effective Date: {{ date('F j, Y') }}
                </p>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">1. Introduction</h2>
                    <p>
                        These Terms of Service ("Terms") govern the relationship between SMART CREATOR AI LLC ("Administration") and users of the NatalScope service ("Service").
                    </p>
                    <p class="mt-4">
                        By using the Service, you confirm that you have read these Terms, understand them, and agree to comply with them. If you do not agree with the Terms, please discontinue use of the Service.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">2. Service Description</h2>
                    <p>
                        NatalScope provides the following features:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Calculate natal charts based on date, time, and place of birth</li>
                        <li>View planetary positions in zodiac signs and houses</li>
                        <li>View aspects between planets</li>
                        <li>Receive personalized astrological interpretations using artificial intelligence</li>
                        <li>Chat with an AI astrologer for answers to questions</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">3. Informational Nature of Services</h2>
                    <p>
                        <strong class="text-white">Important:</strong> The Service provides information solely for entertainment and educational purposes. Astrology is not a science, and calculation results should not be considered as:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Medical recommendations or diagnoses</li>
                        <li>Financial or investment advice</li>
                        <li>Legal consultations</li>
                        <li>Psychological help or therapy</li>
                        <li>Guidance for making important life decisions</li>
                    </ul>
                    <p class="mt-4">
                        The Administration is not responsible for decisions made based on information obtained through the Service.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">4. Registration and Account</h2>
                    <p>
                        Some features of the Service require providing an email address. You agree to:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Provide accurate information</li>
                        <li>Not share access to your account with third parties</li>
                        <li>Immediately notify the Administration of any unauthorized access to your account</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">5. Intellectual Property Rights</h2>
                    <p>
                        All Service materials, including design, text, graphics, software code, calculation algorithms, and interpretations, are the intellectual property of SMART CREATOR AI LLC or are used under license.
                    </p>
                    <p class="mt-4">
                        Copying, distribution, modification, or use of Service materials without written permission from the Administration is prohibited.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">6. Use of AI Technologies</h2>
                    <p>
                        The Service uses artificial intelligence technologies to generate personalized interpretations and responses to user questions. You understand and agree that:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>AI-generated content is created automatically and may contain inaccuracies</li>
                        <li>AI astrologer responses do not replace consultation with qualified professionals</li>
                        <li>The Administration does not guarantee the accuracy, completeness, or applicability of AI-generated content</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">7. Prohibited Actions</h2>
                    <p>When using the Service, the following actions are prohibited:</p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Disrupting Service operation or attempting unauthorized access</li>
                        <li>Using automated tools for mass data collection</li>
                        <li>Distributing malicious software</li>
                        <li>Using the Service for illegal purposes</li>
                        <li>Impersonating another person</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">8. Limitation of Liability</h2>
                    <p>
                        The Service is provided "as is" without any warranties, express or implied. The Administration does not guarantee:
                    </p>
                    <ul class="list-disc pl-6 space-y-2 mt-4">
                        <li>Uninterrupted Service operation</li>
                        <li>Absence of errors or inaccuracies</li>
                        <li>Results meeting your expectations</li>
                    </ul>
                    <p class="mt-4">
                        To the maximum extent permitted by applicable law, the Administration is not liable for any direct, indirect, incidental, or consequential damages related to the use of the Service.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">9. Changes to Terms</h2>
                    <p>
                        The Administration reserves the right to modify these Terms at any time. The current version of the Terms is always available on this page. By continuing to use the Service after changes are made, you agree to the updated Terms.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">10. Governing Law</h2>
                    <p>
                        These Terms are governed by and construed in accordance with applicable law. All disputes arising from the use of the Service shall be resolved in accordance with applicable legislation.
                    </p>
                </section>

                <section>
                    <h2 class="text-xl font-bold text-white mt-8 mb-4">11. Contact Information</h2>
                    <p>
                        For any questions regarding the use of the Service, please contact:
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
                    <a href="{{ url('/privacy') }}" class="text-indigo-400 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ url('/terms') }}" class="text-white font-medium">Terms of Service</a>
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
