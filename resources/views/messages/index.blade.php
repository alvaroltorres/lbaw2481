@extends('layouts.app')

@section('content')
    <main>
        <div class="container">
            <h1>{{ __('Messages') }}</h1>
            <p>{{ __('Here are your existing chats. Click on one to view the messages on the right side.') }}</p>

            <div class="chat-container">
                <div class="chat-list">
                    <h2 class="chat-list-header">{{ __('Chats') }}</h2>
                    <div class="chat-list-messages" id="chatListContainer">
                        @if($auctions->count() > 0)
                            <ul id="chatListUl" style="list-style:none; margin:0; padding:0;">

                                @foreach($auctions as $a)
                                    @php

                                    @endphp
                                    <li>
                                        <button class="auction-chat-btn" data-auction-id="{{ $a->auction_id }}">
                                            <strong>{{ $a->title }}</strong><br>
                                            <small>{{ __('Auction by ') . $a->seller->fullname }}</small>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p id="noChatsMessage" style="padding:1rem;">
                                {{ __('You do not have any chats yet.') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="chat-area">
                    <div class="chat-area-header">
                        <h2 id="chatTitle">{{ __('Chat') }}</h2>
                        <button id="refreshBtn"> ↻ </button>
                    </div>

                    <div id="messagesList" class="messages-list">
                        <p id="noChatSelected" style="text-align:center; color:#999;">
                            {{ __('Select a chat on the left to see the messages.') }}
                        </p>
                    </div>

                    <form id="messageForm" class="message-form" style="display:none;">
                        <input type="text" name="text" id="messageInput" class="form-control" placeholder="{{ __('Type your message...') }}">
                        <button type="submit" class="btn btn--send">{{ __('Send') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <style>
        .auction-chat-btn {
            cursor: pointer;
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
            padding: 1rem;
            margin: 0;
            border-bottom: 1px solid #ccc;
            font-size: 1.2rem;
            background: #fff;
        }
        .chat-list-messages {
            max-height: 400px;
            overflow: auto;
        }
        .chat-area {
            width: 70%;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: #fff;
            display: flex;
            flex-direction: column;
        }
        .chat-area-header {
            padding: 1rem;
            border-bottom: 1px solid #ccc;
            background: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .messages-list {
            flex-grow: 1;
            overflow: auto;
            padding: 1rem;
            background: #f9f9f9;
        }
        .message-form {
            padding: 1rem;
            border-top: 1px solid #ccc;
            background: #fff;
            display: flex;
            gap: 10px;
        }
        #chatTitle {
            font-size: 1rem;
            margin: 0;
        }
        #chatTitle a {
            text-decoration: none;
            color: #333;
            font-weight: 600;
        }
        #chatTitle a:hover {
            color: #007bff;
            text-decoration: underline;
        }
        #chatTitle small {
            display: block;
            font-size: 0.8rem;
            color: #666;
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

            // Map of auctions
            let auctionDetails = @json($auctions->mapWithKeys(function($a) {
                return [$a->auction_id => ['title' => $a->title, 'seller_name' => 'Auction by '.$a->seller->fullname]];
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
                            alert('{{ __("Error sending message.") }}');
                        }
                    })
                    .catch(err => {
                        console.error('{{ __("Error sending message:") }}', err);
                        alert('{{ __("An error occurred while sending the message.") }}');
                    });
            });

            refreshBtn.addEventListener('click', () => {
                if (currentAuctionId) {
                    loadChat(currentAuctionId);
                }
            });

            function loadChat(auctionId) {
                messagesList.innerHTML = `<p class="loading-message">{{ __("Loading messages...") }}</p>`;

                fetch('{{ route("messages.loadChat") }}?auction_id=' + auctionId, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Request failed. Status: ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        currentAuctionId = auctionId;
                        messagesList.innerHTML = '';
                        if (data.messages.length === 0) {
                            messagesList.innerHTML =
                                `<p style="text-align:center; color:#999;">
                                    {{ __("No messages yet. Send one to start the conversation.") }}
                                </p>`;
                            lastMessageId = 0;
                        } else {
                            data.messages.forEach(msg => appendMessage(msg));
                            lastMessageId = data.messages[data.messages.length - 1].message_id;
                        }
                        noChatSelected.style.display = 'none';
                        messageForm.style.display = 'flex';
                        chatTitle.innerHTML = `<a href="/auctions/${auctionId}">${auctionDetails[auctionId].title}</a>
                                               <small>${auctionDetails[auctionId].seller_name}</small>`;
                        messagesList.scrollTop = messagesList.scrollHeight;

                        // Update the URL without reloading the page
                        history.replaceState({}, '', '?auction_id=' + auctionId);

                        startPolling();
                    })
                    .catch(err => {
                        console.error('{{ __("Error loading chat:") }}', err);
                        messagesList.innerHTML =
                            `<p style="text-align:center; color:red;">
                                {{ __("An error occurred while loading the chat.") }}
                            </p>`;
                    });
            }

            function appendMessage(msg) {
                const div = document.createElement('div');
                div.style.marginBottom = '10px';
                div.style.padding = '10px';
                div.style.borderRadius = '5px';
                div.style.maxWidth = '60%';
                div.style.wordWrap = 'break-word';

                div.innerHTML = `
                    <div style="font-size:0.9rem; margin-bottom:5px; color:#666;">
                        ${msg.is_me ? '{{ __("You") }}' : msg.sender_name} - ${msg.time}
                    </div>
                    <div style="font-size:1rem;">
                        ${msg.text}
                    </div>`;

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
                        console.error('{{ __("Error during message polling:") }}', err);
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
                            data.auctions.forEach(a => {
                                auctionDetails[a.auction_id] = {
                                    title: a.title,
                                    seller_name: a.seller_name
                                };
                            });
                            updateChatList(data.auctions);
                            currentAuctions = newAuctions;
                        }
                    })
                    .catch(err => {
                        console.error('{{ __("Error during chat polling:") }}', err);
                    });
            }

            function updateChatList(auctions) {
                let html = '';
                if (auctions.length === 0) {
                    html = `<p id="noChatsMessage" style="padding:1rem;">
                                {{ __('You do not have any chats yet.') }}
                    </p>`;
                } else {
                    html = '<ul id="chatListUl" style="list-style:none; margin:0; padding:0;">';
                    auctions.forEach(a => {
                        let otherUser = a.seller_name;
                        if (a.seller_id === {{ auth()->id() }}) {
                            otherUser = a.participant_name;
                        }

                        html += `
                            <li>
                                <button class="auction-chat-btn" data-auction-id="${a.auction_id}" style="cursor:pointer;">
                                    <strong>${a.title}</strong><br>
                                    <small>${a.other_user}</small>
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
