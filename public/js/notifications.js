document.addEventListener('DOMContentLoaded', function () {
    function checkNotifications() {
        fetch('/notifications/fetch', {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error(`${window.translations.notification.errorFetching}: ${response.status}`, text);
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(notifications => {
                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        // marcar como lida imediatamente -- esta mal isto, devia ser só quando o utilizador clicar, mas por agora fica assim ( deviamos ter um argumento para marcar como popped )
                        markAsRead(notification.id);
                        showNotification(notification);
                    });
                }
            })
            .catch(error => console.error(`${window.translations.notification.errorFetching}:`, error));
    }

    //exibir o popup de notificação
    function showNotification(notification) {
        // Cria o elemento do popup
        const popup = document.createElement('div');
        popup.className = 'notification-popup';

        // prepara a mensagem
        let messageTemplate = window.translations.notification.newBid;
        const message = messageTemplate
            .replace(':amount', notification.data.bid_amount_formatted)
            .replace(':auction', notification.data.auction_title)
            .replace(':bidder', notification.data.bidder_name);

        // conteúdo da notificação
        popup.innerHTML = `
            <div class="notification-content">
                <p>${message}</p>
                <a href="/notifications/show/${notification.id}" class="notification-link">${window.translations.notification.viewDetails}</a>
            </div>
            <button class="notification-close">&times;</button>
        `;

        //  fechar
        popup.querySelector('.notification-close').addEventListener('click', () => {
            popup.remove();
        });

        document.body.appendChild(popup);

        // remove o popup após 10 segundos
        setTimeout(() => {
            popup.remove();
        }, 10000);
    }

    function updateNotificationCount() {
        fetch('/notifications/unread-count', {
            headers: {
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(response => response.json())
            .then(data => {
                const count = data.count;
                const notificationCountElement = document.getElementById('notification-count');
                if (notificationCountElement) {
                    if (count > 0) {
                        notificationCountElement.textContent = count;
                        notificationCountElement.style.display = 'inline-flex';
                    } else {
                        notificationCountElement.style.display = 'none';
                    }
                } else {
                    console.warn('Elemento notification-count não encontrado no DOM');
                }
            })
            .catch(error => console.error('Erro ao buscar contagem de notificações não lidas:', error));
    }

    function markAsRead(notificationId) {
        fetch(`/notifications/mark-as-read/${notificationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({})
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(() => {
                // atualiza a contagem de notificações
                updateNotificationCount();
            })
            .catch(error => console.error('Erro ao marcar notificação como lida:', error));
    }

    // contagem de notificações a cada 5 segundos
    setInterval(updateNotificationCount, 5000);

    updateNotificationCount();

    setInterval(checkNotifications, 5000);

    checkNotifications();
});
