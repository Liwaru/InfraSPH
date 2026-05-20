<link rel="stylesheet" href="{{ asset('css/views/chatbot.css') }}">

<div class="chatbot-shell" id="chatbotShell">
    <div class="chatbot-panel" id="chatbotPanel">
        <div class="chatbot-hero">
            <div class="chatbot-header">
                <button type="button" class="chatbot-header-brand" id="chatbotReset" aria-label="Hapus dan mulai baru">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span class="chatbot-header-brand-label">Hapus & mulai baru</span>
                </button>
                <button type="button" class="chatbot-close" id="chatbotClose" aria-label="Tutup chatbot">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <div class="chatbot-intro">
                <div class="chatbot-title">Get <span>help</span> with InfraSPH</div>
                <div class="chatbot-subtitle"></div>
            </div>
        </div>

        <div class="chatbot-body">
            <div class="chatbot-conversation" id="chatbotConversation"></div>
            <div class="chatbot-typing" id="chatbotTyping">
                <div class="chatbot-typing-bubble">
                    <span id="chatbotTypingText">Chatbot sedang menjawab</span>
                    <span class="chatbot-typing-dots" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </div>
            </div>
        </div>

        <div class="chatbot-footer">
            <div class="chatbot-input">
                <input type="text" value="" placeholder="Atau ketik pertanyaanmu di sini..." aria-label="Pesan chatbot" id="chatbotInput">
                <button type="button" class="chatbot-send" aria-label="Kirim pesan" id="chatbotSend">
                    <i class="bi bi-arrow-up"></i>
                </button>
            </div>
            <div class="chatbot-note">Pilih topik bantuan yang tersedia agar jawaban lebih cepat dan sesuai akses akunmu.</div>
        </div>
    </div>

    <button type="button" class="chatbot-fab" id="chatbotToggle" aria-label="Buka chatbot" aria-expanded="false">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

<script>
    (function () {
        const appShell = document.getElementById('appShell');
        const chatbotShell = document.getElementById('chatbotShell');
        const chatbotToggle = document.getElementById('chatbotToggle');
        const chatbotClose = document.getElementById('chatbotClose');
        const chatbotConversation = document.getElementById('chatbotConversation');
        const chatbotInput = document.getElementById('chatbotInput');
        const chatbotSend = document.getElementById('chatbotSend');
        const chatbotTyping = document.getElementById('chatbotTyping');
        const chatbotTypingText = document.getElementById('chatbotTypingText');
        const chatbotReset = document.getElementById('chatbotReset');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const defaultChatbotPlaceholder = 'Atau ketik pertanyaanmu di sini...';
        let chatbotContextLoaded = false;
        let chatbotBusy = false;
        let chatbotCooldownTimer = null;
        let chatbotPendingInput = null;

        function wait(ms) {
            return new Promise(function (resolve) {
                window.setTimeout(resolve, ms);
            });
        }

        function chatbotReplyDelay() {
            return 1000 + Math.floor(Math.random() * 1000);
        }

        function setChatbotOpen(isOpen) {
            if (!chatbotShell || !chatbotToggle || !appShell) {
                return;
            }

            chatbotShell.classList.toggle('open', isOpen);
            appShell.classList.toggle('chatbot-open', isOpen);
            chatbotToggle.setAttribute('aria-expanded', String(isOpen));
        }

        function refreshChatStartedState() {
            if (!chatbotShell || !chatbotConversation) {
                return;
            }

            const userMessages = chatbotConversation.querySelectorAll('.chatbot-message.user');
            chatbotShell.classList.toggle('chat-started', userMessages.length > 0);
        }

        function setChatbotBusy(isBusy) {
            chatbotBusy = isBusy;

            if (chatbotInput) {
                chatbotInput.disabled = isBusy;
            }

            if (chatbotSend) {
                chatbotSend.disabled = isBusy;
            }
        }

        function startCooldown(seconds) {
            const duration = Math.max(1, Number(seconds || 0));
            let remaining = duration;

            if (chatbotCooldownTimer) {
                window.clearInterval(chatbotCooldownTimer);
            }

            setChatbotBusy(true);

            if (chatbotInput) {
                chatbotInput.placeholder = 'Tunggu ' + remaining + ' detik sebelum kirim lagi...';
            }

            chatbotCooldownTimer = window.setInterval(function () {
                remaining -= 1;

                if (remaining <= 0) {
                    window.clearInterval(chatbotCooldownTimer);
                    chatbotCooldownTimer = null;
                    setChatbotBusy(false);
                    if (chatbotInput) {
                        chatbotInput.placeholder = chatbotPendingInput?.placeholder || defaultChatbotPlaceholder;
                    }
                    return;
                }

                if (chatbotInput) {
                    chatbotInput.placeholder = 'Tunggu ' + remaining + ' detik sebelum kirim lagi...';
                }
            }, 1000);
        }

        function applyPendingInputState(pendingInput) {
            chatbotPendingInput = pendingInput && pendingInput.mode ? pendingInput : null;

            if (chatbotInput) {
                chatbotInput.placeholder = chatbotPendingInput?.placeholder || defaultChatbotPlaceholder;
            }
        }

        function setChatbotStatus(message, visible) {
            if (!chatbotTyping) {
                return;
            }

            if (chatbotTypingText && message) {
                chatbotTypingText.textContent = message;
            } else if (chatbotTypingText) {
                chatbotTypingText.textContent = 'Chatbot sedang menjawab';
            }

            chatbotTyping.classList.toggle('visible', visible);

            if (visible && chatbotConversation) {
                chatbotConversation.scrollTop = chatbotConversation.scrollHeight;
                chatbotTyping.scrollIntoView({ behavior: 'smooth', block: 'end' });
            }
        }

        function createMessageElement(content, role) {
            const item = document.createElement('div');
            item.className = 'chatbot-message ' + role;
            item.textContent = content;
            return item;
        }

        function appendOptions(options, style) {
            if (!chatbotConversation) {
                return;
            }

            const normalizedOptions = Array.isArray(options) ? options.filter(function (option) {
                return option && option.id && option.label;
            }) : [];

            if (!normalizedOptions.length) {
                return;
            }

            const group = document.createElement('div');
            group.className = 'chatbot-option-group ' + (style === 'grid' ? 'grid' : 'list');

            normalizedOptions.forEach(function (option) {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'chatbot-option-btn';

                const iconWrap = document.createElement('span');
                iconWrap.className = 'chatbot-option-icon';
                const icon = document.createElement('i');
                icon.className = String(option.icon || 'bi bi-arrow-right').replace(/[^\w\s-]/g, '');
                iconWrap.appendChild(icon);

                const label = document.createElement('span');
                label.className = 'chatbot-option-label';
                label.textContent = option.label;

                const arrowWrap = document.createElement('span');
                arrowWrap.className = 'chatbot-option-arrow';
                const arrow = document.createElement('i');
                arrow.className = 'bi bi-chevron-right';
                arrowWrap.appendChild(arrow);

                button.append(iconWrap, label, arrowWrap);

                button.addEventListener('click', function () {
                    sendChatbotMessage(option.label, option.id);
                });

                group.appendChild(button);
            });

            chatbotConversation.appendChild(group);
            chatbotConversation.scrollTop = chatbotConversation.scrollHeight;
        }

        function appendMessage(content, role, options, optionStyle) {
            if (!chatbotConversation) {
                return;
            }

            if (role === 'assistant') {
                setChatbotStatus('', false);
            }

            const item = createMessageElement(content, role);
            chatbotConversation.appendChild(item);

            if (role === 'assistant') {
                appendOptions(options, optionStyle);
            }

            chatbotConversation.scrollTop = chatbotConversation.scrollHeight;
            refreshChatStartedState();
        }

        async function resetConversation() {
            if (!chatbotConversation) {
                return;
            }

            try {
                await fetch("{{ route('chatbot.reset') }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });
            } catch (error) {
                // Keep local reset even if server reset fails.
            }

            chatbotConversation.innerHTML = '';
            chatbotContextLoaded = false;
            if (chatbotInput) {
                chatbotInput.value = '';
            }
            applyPendingInputState(null);
            setChatbotStatus('', false);
            refreshChatStartedState();
            await loadChatbotContext(true);
            if (chatbotInput) {
                chatbotInput.focus();
            }
        }

        async function loadChatbotContext(forceReload) {
            if (chatbotContextLoaded && !forceReload) {
                return;
            }

            setChatbotStatus('Memuat akses chatbot...', true);

            try {
                const response = await fetch("{{ route('chatbot.context') }}", {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                const payload = await response.json();
                const context = payload.data || {};
                const assistant = context.assistant || {};

                if (chatbotConversation && !chatbotConversation.querySelector('.chatbot-message')) {
                    appendMessage(
                        assistant.initial_message || 'Halo, saya siap membantu. Pilih salah satu topik bantuan di bawah ini.',
                        'assistant',
                        assistant.initial_options || [],
                        assistant.initial_option_style || 'grid'
                    );
                }

                applyPendingInputState(context.pending_input || null);
                chatbotContextLoaded = true;
                setChatbotStatus('', false);
            } catch (error) {
                setChatbotStatus('Konteks chatbot gagal dimuat. Coba lagi.', true);
            }
        }

        async function sendChatbotMessage(message, optionId) {
            const trimmedMessage = String(message || '').trim();

            if (!trimmedMessage || chatbotBusy) {
                return;
            }

            appendMessage(trimmedMessage, 'user');

            if (chatbotInput) {
                chatbotInput.value = '';
            }

            setChatbotBusy(true);
            setChatbotStatus('Chatbot sedang menjawab...', true);

            try {
                const response = await fetch("{{ route('chatbot.ask') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        message: trimmedMessage,
                        option_id: optionId || null,
                    }),
                });

                const payload = await response.json();

                if (response.status === 429 && payload.data?.cooldown) {
                    await wait(chatbotReplyDelay());
                    appendMessage('Mohon tunggu ' + payload.data.retry_after + ' detik sebelum mengirim pesan berikutnya.', 'assistant');
                    setChatbotStatus('', false);
                    startCooldown(payload.data.retry_after);
                    return;
                }

                const reply = payload.data?.message || 'Maaf, saya belum bisa memproses pertanyaan itu.';
                await wait(chatbotReplyDelay());
                applyPendingInputState(payload.data?.pending_input || null);
                appendMessage(
                    reply,
                    'assistant',
                    payload.data?.options || [],
                    payload.data?.option_style || 'list'
                );
                setChatbotStatus('', false);
            } catch (error) {
                await wait(chatbotReplyDelay());
                appendMessage('Terjadi kendala saat menghubungi chatbot. Silakan coba lagi.', 'assistant');
                setChatbotStatus('', false);
            } finally {
                if (!chatbotCooldownTimer) {
                    setChatbotBusy(false);
                }
            }
        }

        if (chatbotToggle) {
            chatbotToggle.addEventListener('click', async function () {
                const isOpen = chatbotShell?.classList.contains('open');
                setChatbotOpen(!isOpen);

                if (!isOpen) {
                    await loadChatbotContext(false);
                    if (chatbotInput) {
                        chatbotInput.focus();
                    }
                }
            });
        }

        if (chatbotClose) {
            chatbotClose.addEventListener('click', function () {
                setChatbotOpen(false);
            });
        }

        if (chatbotSend) {
            chatbotSend.addEventListener('click', function () {
                sendChatbotMessage(chatbotInput?.value || '');
            });
        }

        if (chatbotInput) {
            chatbotInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    sendChatbotMessage(chatbotInput.value);
                }
            });
        }

        if (chatbotReset) {
            chatbotReset.addEventListener('click', async function () {
                await resetConversation();
            });
        }

        refreshChatStartedState();
    })();
</script>

