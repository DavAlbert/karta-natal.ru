<?php

return [
    // System prompts for AI
    'report_system_prompt' => 'You are an expert in Vedic and Western astrology. Your task is to provide deep, mystical yet practical answers based on the client\'s natal chart. Always reference specific planets, signs, and houses from the client\'s chart.',
    'report_user_prompt' => "Generate a detailed astrological report including:\n1. Personality analysis (Sun, Moon, Ascendant)\n2. Love and relationship analysis (Venus, Mars, 7th House)\n3. Career analysis (10th House, Saturn, MC)\n4. Karmic analysis (Nodes, Saturn, retrograde planets)\n5. Forecast for the upcoming period",
    'chat_system_prompt' => "You are a wise and professional astrologer, expert in Vedic and Western astrology. Your client has provided their natal chart:\n\n:context\n\nYour rules:\n1. Always reference specific planets, signs, and houses from the client's chart\n2. Give practical advice based on astrology\n3. If clarification is needed — ask\n4. Do not fabricate data that is not in the chart",
    'chat_error' => 'Sorry, I cannot respond right now. Please try again later.',
    'chat_error_stars' => 'Sorry, the stars are silent right now. Please try again later.',
    'compatibility_system_prompt' => "You are a professional astrology consultant. RULES:\n1. ALWAYS name specific planets and signs from the charts\n2. ALWAYS explain WHY something happens based on aspects\n3. For problems — give SPECIFIC solutions\n4. Write in simple but professional language\n5. Address partners by name\n6. Describe real life situations based on their charts",

    // Language instruction appended to prompts
    'respond_in_language' => 'Respond ONLY in :language. All text must be in :language.',
    'language_name' => 'English',

    // Chart context labels
    'ctx_natal_chart' => "CLIENT'S NATAL CHART:",
    'ctx_planets' => 'PLANETS:',
    'ctx_houses' => 'HOUSES (cusps):',
    'ctx_key_points' => 'KEY POINTS:',
    'ctx_elements' => 'ELEMENTS:',
    'ctx_dominant_element' => 'Dominant element:',
    'ctx_house' => 'house',
    'ctx_retrograde' => '(retrograde)',
    'ctx_unknown' => 'unknown',

    // Planet names
    'planet_sun' => 'Sun',
    'planet_moon' => 'Moon',
    'planet_mercury' => 'Mercury',
    'planet_venus' => 'Venus',
    'planet_mars' => 'Mars',
    'planet_jupiter' => 'Jupiter',
    'planet_saturn' => 'Saturn',
    'planet_uranus' => 'Uranus',
    'planet_neptune' => 'Neptune',
    'planet_pluto' => 'Pluto',
    'planet_north_node' => 'North Node',
    'planet_south_node' => 'South Node',
    'planet_chiron' => 'Chiron',
    'planet_part_fortune' => 'Part of Fortune',

    // Element names
    'element_fire' => 'Fire',
    'element_earth' => 'Earth',
    'element_air' => 'Air',
    'element_water' => 'Water',

    // Sign names
    'sign_aries' => 'Aries',
    'sign_taurus' => 'Taurus',
    'sign_gemini' => 'Gemini',
    'sign_cancer' => 'Cancer',
    'sign_leo' => 'Leo',
    'sign_virgo' => 'Virgo',
    'sign_libra' => 'Libra',
    'sign_scorpio' => 'Scorpio',
    'sign_sagittarius' => 'Sagittarius',
    'sign_capricorn' => 'Capricorn',
    'sign_aquarius' => 'Aquarius',
    'sign_pisces' => 'Pisces',

    // Tool schema descriptions for OpenAI
    'tool_report_desc' => 'Generate a deep psychological and karmic astrology report based on natal chart data.',
    'tool_identity_desc' => 'Deep analysis of personality, ego, and emotional nature (Sun/Moon/Ascendant).',
    'tool_love_desc' => 'Analysis of relationships, love language, and needs (Venus/Mars/7th House).',
    'tool_career_desc' => 'Analysis of professional path, ambition, and success (10th House/Saturn/MC).',
    'tool_karma_desc' => 'Analysis of karmic destiny, lessons, and growth (Nodes/Saturn/Retrogrades).',
    'tool_forecast_desc' => 'A brief forecast for the upcoming major transits or phases impacting the user.',

    // Chat templates
    'chat_category_general' => 'General',
    'chat_category_love' => 'Love',
    'chat_category_career' => 'Career',
    'chat_category_finance' => 'Finance',
    'chat_category_health' => 'Health',
    'chat_category_karma' => 'Karma',
    'chat_template_general' => 'Tell me about my personality based on my Sun, Moon, and Ascendant. What are my strengths and weaknesses?',
    'chat_template_love' => 'Analyze my chart regarding love and relationships. What does Venus in my chart say? What kind of partner suits me?',
    'chat_template_career' => 'Analyze my 10th house and career prospects. What professions suit me? Where can I be most successful?',
    'chat_template_finance' => 'What does my chart say about finances and money? How can I attract abundance?',
    'chat_template_health' => 'What weak spots does my chart show for health? What should I pay attention to?',
    'chat_template_karma' => 'Tell me about my karmic task based on the lunar nodes. What lessons do I need to learn in this life?',

    // Compatibility
    'partner_1' => 'Partner 1',
    'partner_2' => 'Partner 2',
    'synastry_label' => 'SYNASTRY (aspects between charts):',
    'synastry_score' => 'Overall compatibility score:',
    'synastry_harmony' => 'Harmonious aspects:',
    'synastry_tension' => 'Tense aspects:',
    'synastry_harmony_nature' => 'Harmony',
    'synastry_tension_nature' => 'Tension',
];
