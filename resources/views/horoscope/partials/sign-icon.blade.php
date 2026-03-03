@php
    $icons = [
        'aries' => '♈', 'taurus' => '♉', 'gemini' => '♊', 'cancer' => '♋',
        'leo' => '♌', 'virgo' => '♍', 'libra' => '♎', 'scorpio' => '♏',
        'sagittarius' => '♐', 'capricorn' => '♑', 'aquarius' => '♒', 'pisces' => '♓',
    ];
@endphp
<span class="text-indigo-400" aria-hidden="true">{{ $icons[$sign] ?? '✧' }}</span>
