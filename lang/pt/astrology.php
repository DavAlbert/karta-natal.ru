<?php

return [
    // System prompts for AI
    'report_system_prompt' => 'Você é um especialista em astrologia Védica e Ocidental. Sua tarefa é fornecer respostas profundas, místicas e ao mesmo tempo práticas com base no mapa astral do cliente. Sempre faça referência a planetas, signos e casas específicos do mapa do cliente.',
    'report_user_prompt' => "Gere um relatório astrológico detalhado incluindo:\n1. Análise de personalidade (Sol, Lua, Ascendente)\n2. Análise de amor e relacionamentos (Vênus, Marte, Casa 7)\n3. Análise de carreira (Casa 10, Saturno, MC)\n4. Análise cármica (Nodos, Saturno, planetas retrógrados)\n5. Previsão para o próximo período",
    'chat_system_prompt' => "Você é um astrólogo sábio e profissional, especialista em astrologia Védica e Ocidental. Seu cliente forneceu seu mapa astral:\n\n:context\n\nSuas regras:\n1. Sempre faça referência a planetas, signos e casas específicos do mapa do cliente\n2. Dê conselhos práticos baseados na astrologia\n3. Se precisar de esclarecimento — pergunte\n4. Não invente dados que não estejam no mapa",
    'chat_error' => 'Desculpe, não consigo responder agora. Por favor, tente novamente mais tarde.',
    'chat_error_stars' => 'Desculpe, as estrelas estão em silêncio agora. Por favor, tente novamente mais tarde.',
    'compatibility_system_prompt' => "Você é um consultor profissional de astrologia. REGRAS:\n1. SEMPRE nomeie planetas e signos específicos dos mapas\n2. SEMPRE explique POR QUE algo acontece com base nos aspectos\n3. Para problemas — dê soluções ESPECÍFICAS\n4. Escreva em linguagem simples, mas profissional\n5. Trate os parceiros pelo nome\n6. Descreva situações reais da vida com base nos mapas",

    // Language instruction appended to prompts
    'respond_in_language' => 'Responda APENAS em :language. Todo o texto deve estar em :language.',
    'language_name' => 'Portuguese',

    // Chart context labels
    'ctx_natal_chart' => 'MAPA ASTRAL DO CLIENTE:',
    'ctx_planets' => 'PLANETAS:',
    'ctx_houses' => 'CASAS (cúspides):',
    'ctx_key_points' => 'PONTOS-CHAVE:',
    'ctx_elements' => 'ELEMENTOS:',
    'ctx_dominant_element' => 'Elemento dominante:',
    'ctx_house' => 'casa',
    'ctx_retrograde' => '(retrógrado)',
    'ctx_unknown' => 'desconhecido',

    // Planet names
    'planet_sun' => 'Sol',
    'planet_moon' => 'Lua',
    'planet_mercury' => 'Mercúrio',
    'planet_venus' => 'Vênus',
    'planet_mars' => 'Marte',
    'planet_jupiter' => 'Júpiter',
    'planet_saturn' => 'Saturno',
    'planet_uranus' => 'Urano',
    'planet_neptune' => 'Netuno',
    'planet_pluto' => 'Plutão',
    'planet_north_node' => 'Nodo Norte',
    'planet_south_node' => 'Nodo Sul',
    'planet_chiron' => 'Quíron',
    'planet_part_fortune' => 'Roda da Fortuna',

    // Element names
    'element_fire' => 'Fogo',
    'element_earth' => 'Terra',
    'element_air' => 'Ar',
    'element_water' => 'Água',

    // Sign names
    'sign_aries' => 'Áries',
    'sign_taurus' => 'Touro',
    'sign_gemini' => 'Gêmeos',
    'sign_cancer' => 'Câncer',
    'sign_leo' => 'Leão',
    'sign_virgo' => 'Virgem',
    'sign_libra' => 'Libra',
    'sign_scorpio' => 'Escorpião',
    'sign_sagittarius' => 'Sagitário',
    'sign_capricorn' => 'Capricórnio',
    'sign_aquarius' => 'Aquário',
    'sign_pisces' => 'Peixes',

    // Tool schema descriptions for OpenAI
    'tool_report_desc' => 'Gerar um relatório astrológico profundo, psicológico e cármico com base nos dados do mapa astral.',
    'tool_identity_desc' => 'Análise profunda de personalidade, ego e natureza emocional (Sol/Lua/Ascendente).',
    'tool_love_desc' => 'Análise de relacionamentos, linguagem do amor e necessidades (Vênus/Marte/Casa 7).',
    'tool_career_desc' => 'Análise do caminho profissional, ambição e sucesso (Casa 10/Saturno/MC).',
    'tool_karma_desc' => 'Análise do destino cármico, lições e crescimento (Nodos/Saturno/Retrógrados).',
    'tool_forecast_desc' => 'Uma breve previsão dos principais trânsitos ou fases que impactam o usuário.',

    // Chat templates
    'chat_category_general' => 'Geral',
    'chat_category_love' => 'Amor',
    'chat_category_career' => 'Carreira',
    'chat_category_finance' => 'Finanças',
    'chat_category_health' => 'Saúde',
    'chat_category_karma' => 'Carma',
    'chat_template_general' => 'Fale sobre minha personalidade com base no meu Sol, Lua e Ascendente. Quais são meus pontos fortes e fracos?',
    'chat_template_love' => 'Analise meu mapa em relação ao amor e relacionamentos. O que Vênus diz no meu mapa? Que tipo de parceiro combina comigo?',
    'chat_template_career' => 'Analise minha Casa 10 e perspectivas de carreira. Quais profissões combinam comigo? Onde posso ter mais sucesso?',
    'chat_template_finance' => 'O que meu mapa diz sobre finanças e dinheiro? Como posso atrair abundância?',
    'chat_template_health' => 'Quais pontos fracos meu mapa mostra para a saúde? No que devo prestar atenção?',
    'chat_template_karma' => 'Fale sobre minha tarefa cármica com base nos nodos lunares. Quais lições preciso aprender nesta vida?',

    // Compatibility
    'partner_1' => 'Parceiro 1',
    'partner_2' => 'Parceiro 2',
    'synastry_label' => 'SINASTRIA (aspectos entre os mapas):',
    'synastry_score' => 'Pontuação geral de compatibilidade:',
    'synastry_harmony' => 'Aspectos harmoniosos:',
    'synastry_tension' => 'Aspectos tensos:',
    'synastry_harmony_nature' => 'Harmonia',
    'synastry_tension_nature' => 'Tensão',
];
