document.addEventListener('DOMContentLoaded', function () {
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const NOTIFICATION_POLL_INTERVAL = 5000;
    const COUNT_POLL_INTERVAL = 5000;

    function fetchJSON(url, options = {}) {
        return fetch(url, {
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                ...options.headers
            },
            ...options
        }).then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.error(`Error fetching ${url}: ${response.status}`, text);
                    throw new Error(`HTTP error! Status: ${response.status}`);
                });
            }
            return response.json();
        });
    }

    function checkNotifications() {
        fetchJSON('/notifications/fetch')
            .then(notifications => {
                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        // Por agora marcamos como lida ao mostrar
                        markAsRead(notification.id);
                        showNotification(notification);
                    });
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
    }

    function showNotification(notification) {
        // Cria o elemento do popup
        const popup = document.createElement('div');
        popup.className = 'notification-popup';

        const messageTemplate = window.translations.notification.newBid;
        const message = messageTemplate
            .replace(':amount', notification.data.bid_amount_formatted)
            .replace(':auction', notification.data.auction_title)
            .replace(':bidder', notification.data.bidder_name);

        popup.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notification-text">
                    <p>${message}</p>
                    <a href="/notifications/show/${notification.id}" class="notification-link">${window.translations.notification.viewDetails}</a>
                </div>
            </div>
            <button class="notification-close">&times;</button>
        `;

        popup.querySelector('.notification-close').addEventListener('click', () => {
            popup.remove();
        });

        document.body.appendChild(popup);

        setTimeout(() => {
            popup.classList.add('show');
        }, 10);

        // remove o popup após 10 segundos
        setTimeout(() => {
            popup.classList.remove('show');
            setTimeout(() => popup.remove(), 300);
        }, 10000);
    }

    function updateNotificationCount() {
        fetchJSON('/notifications/unread-count')
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
                }
            })
            .catch(error => console.error('Erro ao buscar contagem de notificações não lidas:', error));
    }

    function markAsRead(notificationId) {
        fetch(`/notifications/mark-as-read/${notificationId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
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
                updateNotificationCount();
            })
            .catch(error => console.error('Erro ao marcar notificação como lida:', error));
    }

    setInterval(updateNotificationCount, COUNT_POLL_INTERVAL);
    setInterval(checkNotifications, NOTIFICATION_POLL_INTERVAL);

    updateNotificationCount();
    checkNotifications();
});
