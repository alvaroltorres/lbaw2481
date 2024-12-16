@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <h1>Mensagens</h1>
            <p>Aqui estão os chats que você já possui. Clique em um deles para visualizar as mensagens à direita.</p>

            <div style="display:flex; gap:2rem;">
                <!-- Lista de chats -->
                <div style="width:30%; border:1px solid #ddd; border-radius:4px; background:#f9f9f9;">
                    <h2 style="padding:1rem; margin:0; border-bottom:1px solid #ccc; font-size:1.2rem; background:#fff;">Chats</h2>
                    <div style="max-height:400px; overflow:auto;">
                        @if($auctions->count() > 0)
                            <ul style="list-style:none; margin:0; padding:0;">
                                @foreach($auctions as $a)
                                    <li>
                                        <button class="auction-chat-btn" data-auction-id="{{ $a->auction_id }}"
                                                style="width:100%; text-align:left; padding:0.75rem; border:none; background:none; cursor:pointer; border-bottom:1px solid #ccc;">
                                            <strong>{{ $a->title }}</strong><br>
                                            <small>ID: {{ $a->auction_id }}</small>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p style="padding:1rem;">Você ainda não possui nenhum chat.</p>
                        @endif
                    </div>
                </div>

                <!-- Área do chat -->
                <div style="width:70%; border:1px solid #ddd; border-radius:4px; background:#fff; display:flex; flex-direction:column;">
                    <div style="padding:1rem; border-bottom:1px solid #ccc; background:#fff; display:flex; justify-content:space-between; align-items:center;">
                        <h2 style="margin:0; font-size:1.2rem;" id="chatTitle">Chat</h2>
                        <button id="refreshBtn" style="padding:0.5rem 1rem; background:var(--accent-color); color:#fff; border:none; border-radius:4px; cursor:pointer;">Atualizar</button>
                    </div>

                    <div id="messagesList" style="flex-grow:1; overflow:auto; padding:1rem; background:#f9f9f9;">
                        <p id="noChatSelected" style="text-align:center; color:#999;">Selecione um chat à esquerda para ver as mensagens.</p>
                    </div>

                    <form id="messageForm" style="display:none; padding:1rem; border-top:1px solid #ccc; background:#fff;" class="d-flex">
                        <div style="display:flex; gap:0.5rem;">
                            <input type="text" name="text" id="messageInput" class="form-control" placeholder="Digite sua mensagem..." style="flex-grow:1; border:1px solid #ccc; padding:0.5rem; border-radius:4px;">
                            <button type="submit" class="btn btn--primary" style="border:none; padding:0.5rem 1rem;">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        .auction-chat-btn.selected {
            background-color: #e0f7fa;
            font-weight: bold;
        }
        .loading-message {
            text-align: center;
            color: #777;
            font-style: italic;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const auctionButtons = document.querySelectorAll('.auction-chat-btn');
            const messagesList = document.getElementById('messagesList');
            const noChatSelected = document.getElementById('noChatSelected');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const refreshBtn = document.getElementById('refreshBtn');
            const chatTitle = document.getElementById('chatTitle');

            let currentAuctionId = null;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // ID da última mensagem carregada
            let lastMessageId = 0;
            // Intervalo de polling (em ms)
            const POLL_INTERVAL = 3000;
            let pollIntervalId = null;

            auctionButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const auctionId = btn.getAttribute('data-auction-id');
                    highlightSelectedChat(btn);
                    loadChat(auctionId);
                });
            });

            messageForm.addEventListener('submit', (e) => {
                e.preventDefault();
                if (!currentAuctionId) return;
                const text = messageInput.value.trim();
                if (text === '') return;

                fetch('{{ route("messages.send") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ auction_id: currentAuctionId, text: text })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            appendMessage(data.message);
                            messageInput.value = '';
                            // Atualiza o lastMessageId se necessário
                            if (data.message.message_id > lastMessageId) {
                                lastMessageId = data.message.message_id;
                            }
                        } else {
                            alert('Erro ao enviar mensagem.');
                        }
                    })
                    .catch(err => {
                        console.error('Erro no fetch de sendMessage:', err);
                        alert('Ocorreu um erro ao enviar a mensagem.');
                    });
            });

            refreshBtn.addEventListener('click', () => {
                if (currentAuctionId) {
                    loadChat(currentAuctionId);
                }
            });

            function loadChat(auctionId) {
                messagesList.innerHTML = '<p class="loading-message">Carregando mensagens...</p>';

                fetch('{{ route("messages.loadChat") }}?auction_id=' + auctionId, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Falha na requisição. Status: ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        currentAuctionId = auctionId;
                        messagesList.innerHTML = '';
                        if (data.messages.length === 0) {
                            messagesList.innerHTML = '<p style="text-align:center; color:#999;">Nenhuma mensagem ainda. Envie uma para iniciar a conversa.</p>';
                            lastMessageId = 0;
                        } else {
                            data.messages.forEach(msg => appendMessage(msg));
                            // Define lastMessageId com o ID da última mensagem
                            lastMessageId = data.messages[data.messages.length - 1].message_id;
                        }
                        noChatSelected.style.display = 'none';
                        messageForm.style.display = 'block';
                        chatTitle.textContent = 'Chat do Leilão ID: ' + auctionId;
                        messagesList.scrollTop = messagesList.scrollHeight;

                        // Inicia o polling de novas mensagens
                        startPolling();
                    })
                    .catch(err => {
                        console.error('Erro ao carregar o chat:', err);
                        messagesList.innerHTML = '<p style="text-align:center; color:red;">Ocorreu um erro ao carregar o chat.</p>';
                    });
            }

            function appendMessage(msg) {
                const div = document.createElement('div');
                div.style.marginBottom = '10px';
                div.style.padding = '10px';
                div.style.borderRadius = '5px';
                div.style.maxWidth = '60%';
                div.style.wordWrap = 'break-word';

                div.innerHTML = `<div style="font-size:0.9rem; margin-bottom:5px; color:#666;">${msg.is_me ? 'Você' : msg.sender_name} - ${msg.time}</div><div style="font-size:1rem;">${msg.text}</div>`;

                if (msg.is_me) {
                    div.style.background = '#e0ffe0';
                    div.style.marginLeft = 'auto';
                } else {
                    div.style.background = '#eeeeee';
                    div.style.marginRight = 'auto';
                }
                messagesList.appendChild(div);
                messagesList.scrollTop = messagesList.scrollHeight;
            }

            function highlightSelectedChat(selectedButton) {
                auctionButtons.forEach(btn => btn.classList.remove('selected'));
                selectedButton.classList.add('selected');
            }

            // Polling de novas mensagens
            function startPolling() {
                stopPolling(); // Evita múltiplos intervals
                if (!currentAuctionId) return;
                pollIntervalId = setInterval(pollForNewMessages, POLL_INTERVAL);
            }

            function stopPolling() {
                if (pollIntervalId) {
                    clearInterval(pollIntervalId);
                    pollIntervalId = null;
                }
            }

            function pollForNewMessages() {
                if (!currentAuctionId) return;
                fetch('{{ route("messages.poll") }}?auction_id=' + currentAuctionId + '&last_message_id=' + lastMessageId, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.messages && data.messages.length > 0) {
                            data.messages.forEach(msg => {
                                appendMessage(msg);
                                if (msg.message_id > lastMessageId) {
                                    lastMessageId = msg.message_id;
                                }
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Erro no polling:', err);
                    });
            }

            // Checa se temos um auction_id inicial
            const urlParams = new URLSearchParams(window.location.search);
            const initialAuctionId = urlParams.get('auction_id');
            if (initialAuctionId) {
                const btnToHighlight = document.querySelector(`.auction-chat-btn[data-auction-id="${initialAuctionId}"]`);
                if (btnToHighlight) {
                    highlightSelectedChat(btnToHighlight);
                }
                loadChat(initialAuctionId);
            }
        });
    </script>
@endsection
