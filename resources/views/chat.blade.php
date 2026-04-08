<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IAmue') }} | Chat</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        brandBlue: '#0A3D68',
                        brandBlueDark: '#062B49',
                        brandOrange: '#C96532',
                        brandOrangeSoft: '#E38B59',
                        brandCream: '#F8F3EA',
                        brandSand: '#EEE2D2',
                        brandInk: '#1D3448',
                    },
                    boxShadow: {
                        panel: '0 28px 70px rgba(29, 52, 72, 0.2)',
                    },
                    keyframes: {
                        reveal: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(14px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            },
                        },
                        blink: {
                            '0%, 80%, 100%': {
                                opacity: '.3',
                                transform: 'translateY(0)'
                            },
                            '40%': {
                                opacity: '1',
                                transform: 'translateY(-2px)'
                            },
                        },
                    },
                    animation: {
                        reveal: 'reveal .28s ease-out both',
                        blink: 'blink 1.2s infinite ease-in-out',
                    },
                },
            },
        };
    </script>
    <script src="https://cdn.tailwindcss.com?plugins=typography,forms"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

    <style>
        :root {
            color-scheme: light;
            --page-bg:
                radial-gradient(circle at 10% 10%, rgba(227, 139, 89, 0.2), transparent 34%),
                radial-gradient(circle at 90% 20%, rgba(10, 61, 104, 0.18), transparent 36%),
                linear-gradient(135deg, #f9f4ec 0%, #f0e4d4 45%, #e9ddce 100%);
        }

        body {
            background: var(--page-bg);
        }

        .pattern-grid {
            background-image:
                linear-gradient(rgba(10, 61, 104, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(10, 61, 104, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
        }

        .chat-scroll::-webkit-scrollbar,
        .history-scroll::-webkit-scrollbar {
            width: 10px;
        }

        .chat-scroll::-webkit-scrollbar-thumb,
        .history-scroll::-webkit-scrollbar-thumb {
            background: rgba(10, 61, 104, 0.24);
            border: 2px solid transparent;
            border-radius: 999px;
            background-clip: padding-box;
        }

        .message-bubble p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>

<body class="min-h-screen font-sans text-brandInk antialiased">
    <div class="pattern-grid relative isolate overflow-hidden">
        <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-b from-white/50 to-transparent">
        </div>
        <div class="pointer-events-none absolute -left-20 top-20 h-64 w-64 rounded-full bg-brandOrange/20 blur-3xl">
        </div>
        <div class="pointer-events-none absolute right-0 top-20 h-64 w-64 rounded-full bg-brandBlue/10 blur-3xl"></div>

        <main class="mx-auto grid min-h-screen max-w-7xl gap-6 px-4 py-5 lg:grid-cols-[0.95fr_1.35fr] lg:px-6 lg:py-6">
            <section
                class="relative overflow-hidden rounded-[2rem] border border-white/65 bg-white/80 p-6 shadow-panel backdrop-blur xl:p-8">
                <div class="absolute -right-12 top-8 h-40 w-40 rounded-full bg-brandOrange/10 blur-3xl"></div>

                <div class="relative flex h-full flex-col">
                    <div class="mb-6 flex items-center gap-4">
                        <img src="{{ asset('images/logo-ondaka.png') }}" alt="Logotipo Ondaka IAmue"
                            class="h-16 w-16 rounded-2xl border border-brandOrange/25 bg-white object-contain p-1 shadow-sm">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brandOrange">Ondaka IAmue
                            </p>
                            <p class="text-sm text-brandInk/65">Professor virtual de Umbundo</p>
                        </div>
                    </div>

                    <span
                        class="inline-flex w-fit rounded-full border border-brandBlue/15 bg-brandBlue/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-brandBlue">
                        Conta autenticada
                    </span>

                    <div class="mt-4 rounded-[1.5rem] border border-brandBlue/10 bg-brandCream/80 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.22em] text-brandOrange">Sessao atual</p>
                        <p class="mt-3 text-xl font-semibold text-brandBlue" id="username">{{ $user->name }}</p>
                        <p class="mt-1 text-sm text-brandInk/70">{{ $user->email }}</p>
                        <p class="mt-4 text-sm leading-7 text-brandInk/70">
                            O historico das conversas agora fica guardado na base de dados e ligado a sua conta.
                        </p>
                    </div>

                    <div class="mt-6 flex-1 overflow-hidden rounded-[1.75rem] border border-brandBlue/10 bg-white/85">
                        <div class="border-b border-brandBlue/10 px-5 py-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brandOrange">Conversas
                                recentes</p>
                        </div>

                        <div id="conversation-list" class="history-scroll max-h-[26rem] overflow-y-auto px-3 py-3">
                            @forelse ($conversations as $conversation)
                                <a href="{{ route('chat.index', ['conversa' => $conversation->id]) }}"
                                    data-conversation-id="{{ $conversation->id }}"
                                    class="conversation-link mb-2 block rounded-2xl border px-4 py-3 transition {{ $currentConversationId === $conversation->id ? 'border-brandBlue bg-brandBlue text-black shadow-sm' : 'border-brandBlue/10 bg-brandCream/55 text-brandInk hover:border-brandOrange/30 hover:bg-black/35 hover:bg-white' }}">
                                    <p class="truncate text-sm font-semibold">{{ $conversation->title }}</p>
                                    <p
                                        class="mt-1 text-xs {{ $currentConversationId === $conversation->id ? 'text-black/80' : 'text-black' }}">
                                        Atualizada em {{ $conversation->updated_at->format('d/m H:i') }}
                                    </p>
                                </a>
                            @empty
                                <div id="empty-conversations"
                                    class="rounded-2xl border border-dashed border-brandBlue/15 bg-brandCream/60 px-4 py-5 text-sm leading-7 text-black/65">
                                    Ainda nao existem conversas guardadas. Envie a sua primeira mensagem para criar um
                                    historico.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div
                        class="mt-6 rounded-[1.75rem] bg-gradient-to-br from-brandBlue to-brandBlueDark p-5 text-black">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-brandSand">Sugestoes de estudo
                        </p>
                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-2xl bg-white/10 p-3">
                                <p class="text-sm font-semibold">Saudacoes</p>
                                <p class="mt-1 text-sm text-black/80">Como dizer bom dia e ola.</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-3">
                                <p class="text-sm font-semibold">Dialogos</p>
                                <p class="mt-1 text-sm text-black/80">Treine conversas curtas.</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 p-3">
                                <p class="text-sm font-semibold">Correcao</p>
                                <p class="mt-1 text-sm text-black/80">Peca ajustes nas suas respostas.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                class="flex min-h-[78vh] flex-col overflow-hidden rounded-[2rem] border border-white/70 bg-white/85 shadow-panel backdrop-blur">
                <header
                    class="flex flex-col gap-4 border-b border-brandBlue/10 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('images/logo-ondaka.png') }}" alt="Logotipo Ondaka IAmue"
                            class="h-12 w-12 rounded-xl border border-brandOrange/30 bg-white object-contain p-1">
                        <div>
                            <h2 class="text-xl font-semibold text-brandInk">Sala de conversa</h2>
                            <p class="text-sm text-brandInk/65">
                                @if ($currentConversationId)
                                    A continuar a conversa guardada na base de dados.
                                @else
                                    Inicie uma nova conversa com historico persistente.
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-nowrap items-center gap-3">
                        <button id="new-chat" type="button"
                            class="inline-flex items-center justify-center rounded-full border border-brandBlue/15 bg-white px-4 py-2 text-sm font-medium text-brandInk transition hover:border-brandOrange/35 hover:text-brandOrange">
                            Nova conversa
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center justify-center rounded-full bg-white px-4 py-2 text-sm font-semibold text-black transition hover:bg-black hover:text-white">
                                Terminar sessao
                            </button>
                        </form>
                    </div>
                </header>

                <div id="chat-messages" class="chat-scroll flex-1 space-y-4 overflow-y-auto px-4 py-5 sm:px-6"></div>

                <div
                    class="sticky bottom-0 z-20 border-t border-brandBlue/10 bg-brandSand/75 px-4 py-4 backdrop-blur sm:px-6">
                    <div class="mb-4">
                        <p class="mb-3 text-xs font-semibold uppercase tracking-[0.22em] text-brandInk/55">Experimente
                            estas perguntas</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button"
                                class="prompt-chip rounded-full border border-brandBlue/15 bg-white px-3 py-2 text-sm text-brandInk/75 transition hover:border-brandBlue/35 hover:text-brandBlue"
                                data-message="Como se diz bom dia em Umbundo?">
                                Como se diz bom dia?
                            </button>
                            <button type="button"
                                class="prompt-chip rounded-full border border-brandBlue/15 bg-white px-3 py-2 text-sm text-brandInk/75 transition hover:border-brandBlue/35 hover:text-brandBlue"
                                data-message="Quero praticar uma apresentacao simples em Umbundo.">
                                Quero praticar uma apresentacao
                            </button>
                            <button type="button"
                                class="prompt-chip rounded-full border border-brandBlue/15 bg-white px-3 py-2 text-sm text-brandInk/75 transition hover:border-brandBlue/35 hover:text-brandBlue"
                                data-message="Cria um pequeno exercicio com 3 frases para eu responder.">
                                Da-me um exercicio curto
                            </button>
                        </div>
                    </div>

                    <form id="chat-form" class="rounded-[1.5rem] border border-brandBlue/12 bg-white p-3 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                            <div class="flex-1">
                                <label for="message-input"
                                    class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-brandInk/55">
                                    A sua mensagem
                                </label>
                                <textarea id="message-input" rows="1" maxlength="4000" placeholder="Escreva aqui para conversar com o agente..."
                                    class="max-h-40 min-h-[56px] w-full resize-none rounded-2xl border border-brandBlue/10 bg-brandCream px-4 py-4 text-sm text-brandInk placeholder:text-brandInk/45 focus:border-brandBlue/40 focus:bg-white focus:ring-0"></textarea>
                            </div>

                            <button id="send-button" type="submit"
                                class="inline-flex h-14 w-full items-center justify-center gap-2 rounded-2xl border border-brandOrange bg-brandOrange px-6 text-base font-semibold text-black transition hover:bg-brandOrangeSoft focus:outline-none focus:ring-4 focus:ring-brandOrange/30 disabled:cursor-not-allowed disabled:border-brandOrange/40 disabled:bg-brandOrange/60 sm:w-auto sm:min-w-[150px]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M22 2L15 22L11 13L2 9L22 2Z" />
                                </svg>
                                <span id="send-label">Enviar</span>
                            </button>
                        </div>

                        <div
                            class="mt-3 flex flex-col gap-2 text-xs text-brandInk/55 sm:flex-row sm:items-center sm:justify-between">
                            <p>Pressione <strong>Enter</strong> para enviar e <strong>Shift + Enter</strong> para nova
                                linha.</p>
                            <p>O historico desta conversa fica guardado na sua conta.</p>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        (function($) {
            const starterMessage = {
                papel: 'assistant',
                texto: 'Ola! Sou o teu professor virtual de Umbundo. Podes pedir traducoes, vocabulario, dialogos ou exercicios. Como queres comecar?',
                hora: 'Agora',
            };

            const initialMessages = @json($initialMessages, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            let currentConversationId = @json($currentConversationId);

            const $messages = $('#chat-messages');
            const $form = $('#chat-form');
            const $input = $('#message-input');
            const $sendButton = $('#send-button');
            const $sendLabel = $('#send-label');
            const $user = $('#username');
            const $conversationList = $('#conversation-list');
            let history = Array.isArray(initialMessages) && initialMessages.length > 0 ? initialMessages : [
                starterMessage
            ];
            let typingVisible = false;

            function escapeHtml(text) {
                return $('<div>').text(text).html().replace(/\n/g, '<br>');
            }

            function createTimestamp() {
                return new Date().toLocaleTimeString('pt-AO', {
                    hour: '2-digit',
                    minute: '2-digit',
                });
            }

            function autoResize() {
                $input.css('height', 'auto');
                $input.css('height', Math.min($input[0].scrollHeight, 160) + 'px');
            }

            function scrollToBottom(animated = true) {
                const el = $messages[0];

                if (!el) {
                    return;
                }

                el.scrollTo({
                    top: el.scrollHeight,
                    behavior: animated ? 'smooth' : 'auto',
                });
            }

            function messageCard(message) {
                const isUser = message.papel === 'user';
                const isSystem = message.papel === 'system';
                const wrapperClass = isUser ? 'items-end' : 'items-start';
                const bubbleClass = isUser ?
                    'bg-brandBlue text-black border border-brandBlueDark/40 rounded-[1.4rem] rounded-br-md' :
                    isSystem ?
                    'bg-rose-50 text-rose-900 border border-rose-200 rounded-[1.4rem] rounded-bl-md' :
                    'bg-white text-brandInk border border-brandBlue/12 rounded-[1.4rem] rounded-bl-md shadow-sm';
                const metaClass = isSystem ? 'text-rose-600' : 'text-brandInk/60';
                const badgeClass = isUser ?
                    'bg-brandOrange text-black' :
                    isSystem ?
                    'bg-rose-500 text-black' :
                    'bg-brandBlue/10 text-brandBlue';
                const nome = isUser ? $user.text() : isSystem ? 'Sistema' : 'Professor';

                return `
                        <article class="flex ${wrapperClass} animate-reveal">
                            <div class="max-w-[94%] sm:max-w-[82%]">
                                <div class="mb-1.5 flex items-center gap-2 ${isUser ? 'justify-end' : 'justify-start'}">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] ${badgeClass}">
                                        ${nome}
                                    </span>
                                    <span class="text-[11px] font-semibold ${metaClass}">
                                        ${message.hora || createTimestamp()}
                                    </span>
                                </div>
                                <div class="message-bubble prose prose-sm max-w-none leading-7 ${bubbleClass} px-4 py-3">
                                    <p>${escapeHtml(message.texto)}</p>
                                </div>
                            </div>
                        </article>
                    `;
            }

            function typingCard() {
                return `
                        <article id="typing-indicator" class="flex items-start animate-reveal">
                            <div class="max-w-[75%]">
                                <div class="mb-1.5 flex items-center gap-2">
                                    <span class="inline-flex items-center rounded-full bg-brandBlue/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-brandBlue">
                                        Agente IA
                                    </span>
                                    <span class="text-[11px] font-semibold text-brandInk/55">A escrever...</span>
                                </div>
                                <div class="flex items-center gap-1.5 rounded-[1.4rem] rounded-bl-md border border-brandBlue/12 bg-white px-4 py-3 shadow-sm">
                                    <span class="h-2.5 w-2.5 rounded-full bg-brandBlue/45 animate-blink"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-brandBlue/45 animate-blink [animation-delay:0.2s]"></span>
                                    <span class="h-2.5 w-2.5 rounded-full bg-brandBlue/45 animate-blink [animation-delay:0.4s]"></span>
                                </div>
                            </div>
                        </article>
                    `;
            }

            function renderHistory() {
                $messages.empty();

                history.forEach(function(message) {
                    $messages.append(messageCard(message));
                });

                scrollToBottom(false);
            }

            function appendMessage(message) {
                const entry = {
                    ...message,
                    hora: message.hora || createTimestamp(),
                };

                history.push(entry);
                $messages.append(messageCard(entry));
                scrollToBottom();
            }

            function showTyping() {
                if (typingVisible) {
                    return;
                }

                typingVisible = true;
                $messages.append(typingCard());
                scrollToBottom();
            }

            function hideTyping() {
                typingVisible = false;
                $('#typing-indicator').remove();
            }

            function setBusy(busy) {
                $sendButton.prop('disabled', busy);
                $sendLabel.text(busy ? 'A enviar...' : 'Enviar');
            }

            function createConversationLink(id, title) {
                return $(`
                        <a href="/?conversa=${id}" data-conversation-id="${id}" class="conversation-link mb-2 block rounded-2xl border border-brandBlue bg-brandBlue px-4 py-3 text-white shadow-sm">
                            <p class="truncate text-sm font-semibold">${escapeHtml(title)}</p>
                            <p class="mt-1 text-xs text-white/80">Atualizada agora</p>
                        </a>
                    `);
            }

            function setConversationActive(id) {
                $('.conversation-link').each(function() {
                    const $link = $(this);
                    const isActive = $link.data('conversation-id') === id;

                    $link.toggleClass('border-brandBlue bg-brandBlue text-white shadow-sm', isActive);
                    $link.toggleClass(
                        'border-brandBlue/10 bg-brandCream/55 text-brandInk hover:border-brandOrange/30 hover:bg-white',
                        !isActive);
                    $link.find('p:last').toggleClass('text-white/80', isActive).toggleClass('text-brandInk/55',
                        !isActive);
                });
            }

            function ensureConversationLink(id, title) {
                if (!id || $(`.conversation-link[data-conversation-id="${id}"]`).length) {
                    setConversationActive(id);
                    return;
                }

                $('#empty-conversations').remove();
                $conversationList.prepend(createConversationLink(id, title));
                setConversationActive(id);
            }

            function startNewConversation() {
                currentConversationId = null;
                history = [starterMessage];
                renderHistory();
                setConversationActive(null);
                window.history.replaceState({}, '', '/');
                $input.trigger('focus');
            }

            function sendMessage(text) {
                const content = $.trim(text);

                if (!content) {
                    return;
                }

                appendMessage({
                    papel: 'user',
                    texto: content,
                });

                $input.val('');
                autoResize();
                showTyping();
                setBusy(true);

                $.ajax({
                        url: '/conversar',
                        method: 'POST',
                        contentType: 'application/json; charset=UTF-8',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: JSON.stringify({
                            mensagem: content,
                            conversation_id: currentConversationId,
                        }),
                    })
                    .done(function(response) {
                        if (response.conversation_id && !currentConversationId) {
                            currentConversationId = response.conversation_id;
                            ensureConversationLink(currentConversationId, content);
                            window.history.replaceState({}, '', '/?conversa=' + currentConversationId);
                        } else if (response.conversation_id) {
                            currentConversationId = response.conversation_id;
                            setConversationActive(currentConversationId);
                        }

                        appendMessage({
                            papel: 'assistant',
                            texto: response.resposta ||
                                'Recebi a tua mensagem, mas nao consegui gerar uma resposta agora.',
                        });
                    })
                    .fail(function(xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.conversation_id && !currentConversationId) {
                            currentConversationId = xhr.responseJSON.conversation_id;
                            ensureConversationLink(currentConversationId, content);
                            window.history.replaceState({}, '', '/?conversa=' + currentConversationId);
                        }

                        const errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                            xhr.responseJSON.message :
                            'Nao foi possivel contactar o agente agora. Tenta novamente em instantes.';

                        appendMessage({
                            papel: 'system',
                            texto: errorMessage,
                        });
                    })
                    .always(function() {
                        hideTyping();
                        setBusy(false);
                        $input.trigger('focus');
                    });
            }

            $form.on('submit', function(event) {
                event.preventDefault();
                sendMessage($input.val());
            });

            $input.on('input', autoResize);

            $input.on('keydown', function(event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    $form.trigger('submit');
                }
            });

            $('.prompt-chip').on('click', function() {
                const message = $(this).data('message');
                $input.val(message);
                autoResize();
                $input.trigger('focus');
            });

            $('#new-chat').on('click', startNewConversation);

            renderHistory();
            autoResize();
            setConversationActive(currentConversationId);
            $input.trigger('focus');
        })(jQuery);
    </script>

    @include('partials.footer')
</body>

</html>
