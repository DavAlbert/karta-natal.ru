<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Чат с астрологом — {{ $chart->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #0B0E14;
            --bg-secondary: #11161F;
            --bg-tertiary: #1A212E;
            --border: #2A3441;
            --text-primary: #F8FAFC;
            --text-secondary: #94A3B8;
            --accent-gold: #EAB308;
            --accent-indigo: #818CF8;
        }
        * { box-sizing: border-box; }
        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            padding: 1rem 1.5rem;
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-back {
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }
        .header-back:hover { color: var(--text-primary); }
        .header-title {
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
        }
        .messages {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .message {
            max-width: 85%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .message.user {
            align-self: flex-end;
            background: var(--accent-indigo);
            color: white;
            border-bottom-right-radius: 0.25rem;
        }
        .message.assistant {
            align-self: flex-start;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-bottom-left-radius: 0.25rem;
        }
        .message.typing {
            color: var(--text-secondary);
            font-style: italic;
        }
        .templates {
            padding: 1rem;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
        }
        .templates-title {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .templates-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .template-btn {
            display: flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.6rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 2rem;
            color: var(--text-secondary);
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .template-btn:hover {
            border-color: var(--accent-indigo);
            color: var(--text-primary);
        }
        .input-area {
            padding: 1rem;
            background: var(--bg-secondary);
            border-top: 1px solid var(--border);
        }
        .input-wrapper {
            display: flex;
            gap: 0.5rem;
            align-items: flex-end;
        }
        .input-field {
            flex: 1;
            background: var(--bg-tertiary);
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
            font-size: 0.9rem;
            resize: none;
            max-height: 120px;
            font-family: inherit;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--accent-indigo);
        }
        .input-field::placeholder {
            color: var(--text-muted);
        }
        .send-btn {
            padding: 0.75rem 1.25rem;
            background: var(--accent-indigo);
            border: none;
            border-radius: 0.75rem;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .send-btn:hover {
            background: #6366f1;
        }
        .send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        @media (max-width: 640px) {
            .message { max-width: 90%; }
            .templates { padding: 0.75rem; }
            .input-area { padding: 0.75rem; }
        }
    </style>
</head>
<body>
    <header class="header">
        <a href="{{ route('charts.show', $chart) }}" class="header-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Назад
        </a>
        <h1 class="header-title">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24" style="color:var(--accent-gold)">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
            </svg>
            Астрологический чат
        </h1>
    </header>

    <div class="chat-container">
        <div class="messages" id="messages">
            <div class="message assistant">
                Привет! Я — ваш астрологический помощник. Я изучил вашу натальную карту и готов ответить на ваши вопросы.
                <br><br>
                Вы можете выбрать готовую тему ниже или задать свой вопрос.
            </div>
        </div>

        <div class="templates">
            <div class="templates-title">Быстрые темы</div>
            <div class="templates-grid" id="templates">
                @foreach($templates as $key => $template)
                <button class="template-btn" data-key="{{ $key }}" data-prompt="{{ $template['prompt'] }}">
                    <span>{{ $template['icon'] }}</span>
                    <span>{{ $template['title'] }}</span>
                </button>
                @endforeach
            </div>
        </div>

        <div class="input-area">
            <form id="chat-form" class="input-wrapper">
                @csrf
                <textarea
                    id="message-input"
                    class="input-field"
                    placeholder="Задайте вопрос..."
                    rows="1"
                ></textarea>
                <button type="submit" class="send-btn" id="send-btn">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V5M5 12l7-7 7 7"/>
                    </svg>
                    Отправить
                </button>
            </form>
        </div>
    </div>

    <script>
        const messagesEl = document.getElementById('messages');
        const form = document.getElementById('chat-form');
        const input = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        const templateBtns = document.querySelectorAll('.template-btn');

        // Auto-resize textarea
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        // Template click handler
        templateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const prompt = this.dataset.prompt;
                input.value = prompt;
                input.focus();
            });
        });

        // Add message to chat
        function addMessage(content, role) {
            const div = document.createElement('div');
            div.className = `message ${role}`;
            div.innerHTML = content;
            messagesEl.appendChild(div);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        // Show typing indicator
        function showTyping() {
            const div = document.createElement('div');
            div.className = 'message assistant typing';
            div.id = 'typing';
            div.textContent = 'Печатает...';
            messagesEl.appendChild(div);
            messagesEl.scrollTop = messagesEl.scrollHeight;
        }

        // Remove typing indicator
        function hideTyping() {
            const typing = document.getElementById('typing');
            if (typing) typing.remove();
        }

        // Send message
        async function sendMessage(message) {
            addMessage(message, 'user');
            input.value = '';
            input.style.height = 'auto';
            showTyping();
            sendBtn.disabled = true;

            try {
                const response = await fetch(`/charts/{{ $chart->id }}/chat`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ message })
                });

                hideTyping();

                if (response.ok) {
                    const data = await response.json();
                    addMessage(data.message.replace(/\n/g, '<br>'), 'assistant');
                } else {
                    addMessage('Извините, произошла ошибка. Попробуйте позже.', 'assistant');
                }
            } catch (error) {
                hideTyping();
                addMessage('Извините, произошла ошибка. Попробуйте позже.', 'assistant');
            }

            sendBtn.disabled = false;
        }

        // Form submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;
            await sendMessage(message);
        });

        // Enter key to send (Shift+Enter for new line)
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const message = input.value.trim();
                if (message) sendMessage(message);
            }
        });
    </script>
</body>
</html>
