@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <h1>Mensagens</h1>
            <p>Aqui estão os chats que você já possui. Clique em um deles para visualizar as mensagens à direita.</p>

            <div class="chat-container">
                <div class="chat-list">
                    <h2 class="chat-list-header">Chats</h2>
                    <div class="chat-list-messages" id="chatListContainer">
                        @if($auctions->count() > 0)
                            <ul id="chatListUl" style="list-style:none; margin:0; padding:0;">
                                @foreach($auctions as $a)
                                    <li>
                                        <button class="auction-chat-btn" data-auction-id="{{ $a->auction_id }}">
                                            <strong>{{ $a->title }}</strong><br>
                                            <small>{{ $a->seller->fullname }}</small>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p id="noChatsMessage" style="padding:1rem;">Você ainda não possui nenhum chat.</p>
                        @endif
                    </div>
                </div>

                <div class="chat-area">
                    <div class="chat-area-header">
                        <h2 id="chatTitle">Chat</h2>
                        <button id="refreshBtn">Atualizar</button>
                    </div>

                    <div id="messagesList" class="messages-list">
                        <p id="noChatSelected" style="text-align:center; color:#999;">Selecione um chat à esquerda para ver as mensagens.</p>
                    </div>

                    <form id="messageForm" class="message-form" style="display:none;">
                        <input type="text" name="text" id="messageInput" class="form-control" placeholder="Digite sua mensagem...">
                        <button type="submit" class="btn btn--send">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        .auction-chat-btn {
            cursor: pointer; /* Agora o botão parece clicável */
            width: 100%;
            text-align: left;
            padding: 0.5rem 1rem;
            border: none;
            background: none;
        }

        .auction-chat-btn:hover {
            background-color: #e0f7fa;
        }

        .auction-chat-btn.selected {
            background-color: #e0f7fa;
            font-weight: bold;
        }
        .loading-message {
            text-align: center;
            color: #777;
            font-style: italic;
        }

        .chat-container {
            display: flex;
            gap: 2rem;
        }
        .chat-list {
            width: 30%;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #f9f9f9;
        }
        .chat-list-header {
            padding: 1rem; margin:0;
            border-bottom:1px solid #ccc;
            font-size:1.2rem;
            background:#fff;
        }
        .chat-list-messages {
            max-height:400px; overflow:auto;
        }
        .chat-area {
            width:70%;
            border:1px solid #ddd;
            border-radius:4px;
            background:#fff;
            display:flex;
            flex-direction:column;
        }
        .chat-area-header {
            padding:1rem; border-bottom:1px solid #ccc; background:#fff;
            display:flex; justify-content:space-between; align-items:center;
        }
        .messages-list {
            flex-grow:1; overflow:auto; padding:1rem; background:#f9f9f9;
        }
        .message-form {
            padding:1rem; border-top:1px solid #ccc; background:#fff;
            display:flex; gap:10px;
        }

        /* Novos estilos para o chatTitle */
        #chatTitle {
            font-size: 1rem; /* Reduzir o tamanho da fonte */
            margin: 0;
        }

        #chatTitle a {
            text-decoration: none; /* Remover sublinhado */
            color: #333; /* Cor do link */
            font-weight: 600; /* Peso da fonte para destacar */
        }

        #chatTitle a:hover {
            color: #007bff; /* Cor ao passar o mouse */
            text-decoration: underline; /* Sublinhado ao passar o mouse */
        }

        #chatTitle small {
            display: block;
            font-size: 0.8rem; /* Fonte menor para o nome do vendedor */
            color: #666; /* Cor mais suave para o vendedor */
            margin-top: 0.2rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatListContainer = document.getElementById('chatListContainer');
            const messagesList = document.getElementById('messagesList');
            const noChatSelected = document.getElementById('noChatSelected');
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const refreshBtn = document.getElementById('refreshBtn');
            const chatTitle = document.getElementById('chatTitle');

            let auctionButtons = document.querySelectorAll('.auction-chat-btn');
            let currentAuctionId = null;
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let lastMessageId = 0;
            const POLL_INTERVAL = 3000;
            const CHAT_POLL_INTERVAL = 5000;
            let pollIntervalId = null;
            let chatPollIntervalId = null;

            // Mapeamento de detalhes dos leilões
            let auctionDetails = @json($auctions->mapWithKeys(function($a) {
                return [$a->auction_id => ['title' => $a->title, 'seller_name' => $a->seller->fullname]];
            }));

            function attachAuctionButtonEvents() {
                auctionButtons = document.querySelectorAll('.auction-chat-btn');
                auctionButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const auctionId = btn.getAttribute('data-auction-id');
                        highlightSelectedChat(btn);
                        loadChat(auctionId);
                    });
                });
            }

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
                            if (data.message.message_id > lastMessageId) {
                                lastMessageId = data.message.message_id;
                            }
                        } else {
                            alert('Erro ao enviar mensagem.');
                        }
                    })
                    .catch(err => {
                        console.error('Erro ao enviar mensagem:', err);
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
                            lastMessageId = data.messages[data.messages.length - 1].message_id;
                        }
                        noChatSelected.style.display = 'none';
                        messageForm.style.display = 'flex';
                        chatTitle.innerHTML = `<a href="/auctions/${auctionId}">${auctionDetails[auctionId].title}</a><small>${auctionDetails[auctionId].seller_name}</small>`;
                        messagesList.scrollTop = messagesList.scrollHeight;

                        // Atualiza a URL sem recarregar a página
                        history.replaceState({}, '', '?auction_id=' + auctionId);

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

            function startPolling() {
                stopPolling();
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
                        console.error('Erro no polling de mensagens:', err);
                    });
            }

            let currentAuctions = @json($auctions->pluck('auction_id'));

            function startChatPolling() {
                stopChatPolling();
                chatPollIntervalId = setInterval(pollForNewChats, CHAT_POLL_INTERVAL);
            }

            function stopChatPolling() {
                if (chatPollIntervalId) {
                    clearInterval(chatPollIntervalId);
                    chatPollIntervalId = null;
                }
            }

            function pollForNewChats() {
                fetch('{{ route("messages.pollChats") }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.auctions) return;

                        const newAuctions = data.auctions.map(a => a.auction_id);
                        if (newAuctions.length !== currentAuctions.length ||
                            !newAuctions.every(id => currentAuctions.includes(id))) {
                            // Atualiza auctionDetails com os novos leilões
                            data.auctions.forEach(a => {
                                auctionDetails[a.auction_id] = { title: a.title, seller_name: a.seller_name };
                            });
                            updateChatList(data.auctions);
                            currentAuctions = newAuctions;
                        }
                    })
                    .catch(err => {
                        console.error('Erro no polling de chats:', err);
                    });
            }

            function updateChatList(auctions) {
                let html = '';
                if (auctions.length === 0) {
                    html = '<p id="noChatsMessage" style="padding:1rem;">Você ainda não possui nenhum chat.</p>';
                } else {
                    html = '<ul id="chatListUl" style="list-style:none; margin:0; padding:0;">';
                    auctions.forEach(a => {
                        html += `<li>
                    <button class="auction-chat-btn" data-auction-id="${a.auction_id}" style="cursor:pointer;">
                        <strong>${a.title}</strong><br>
                        <small>ID: ${a.auction_id}</small>
                    </button>
                </li>`;
                    });
                    html += '</ul>';
                }
                chatListContainer.innerHTML = html;
                attachAuctionButtonEvents();

                if (currentAuctionId) {
                    const btnToHighlight = document.querySelector(`.auction-chat-btn[data-auction-id="${currentAuctionId}"]`);
                    if (btnToHighlight) {
                        highlightSelectedChat(btnToHighlight);
                    }
                }
            }

            attachAuctionButtonEvents();
            startChatPolling();

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
