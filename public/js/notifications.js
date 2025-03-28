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
    // Mark as read when displayed (or handle differently if needed)
    markAsRead(notification.id);
    showNotification(notification);
});
}
})
    .catch(error => console.error('Error fetching notifications:', error));
}

    function showNotification(notification) {
    const notificationType = notification.data.notification_type;
    const isOwner = notification.data.is_owner;

    let messageTemplate;

    switch (notificationType) {
    case 'auction_ending':  // FR.502
    messageTemplate = isOwner
    ? window.translations.notification.auctionEndingOwner
    : window.translations.notification.auctionEndingParticipant;
    break;

    case 'auction_ended':   // FR.503
    messageTemplate = isOwner
    ? window.translations.notification.auctionEndedOwner
    : window.translations.notification.auctionEndedParticipant;
    break;

    case 'auction_canceled': // FR.504
    messageTemplate = isOwner
    ? window.translations.notification.auctionCanceledOwner
    : window.translations.notification.auctionCanceledParticipant;
    break;

    case 'auction_winner':   // FR.505
    messageTemplate = isOwner
    ? window.translations.notification.auctionWinnerOwner
    : window.translations.notification.auctionWinnerParticipant;
    break;

    case 'new_bid':
    messageTemplate = isOwner
    ? window.translations.notification.newBidOwner
    : window.translations.notification.newBidParticipant;
    break;

    default:
    messageTemplate = __('You have a new notification.');
    break;
}

    if (typeof messageTemplate !== 'string') {
    console.error('Invalid message template:', messageTemplate);
    messageTemplate = __('You have a new notification.');
}

    // Replace placeholders
    let message = messageTemplate;

    if (message.includes(':auction') && notification.data.auction_title) {
    message = message.replace(':auction', notification.data.auction_title);
}
    if (message.includes(':end_time') && notification.data.end_time) {
    message = message.replace(':end_time', notification.data.end_time);
}
    if (message.includes(':reason') && notification.data.cancel_reason) {
    message = message.replace(':reason', notification.data.cancel_reason);
}
    if (message.includes(':winner') && notification.data.winner_name) {
    message = message.replace(':winner', notification.data.winner_name);
}
    if (message.includes(':bidder') && notification.data.bidder_name) {
    message = message.replace(':bidder', notification.data.bidder_name);
}
    if (message.includes(':amount') && notification.data.bid_amount_formatted) {
    message = message.replace(':amount', notification.data.bid_amount_formatted);
}

    // Create popup
    const popup = document.createElement('div');
    popup.className = 'notification-popup';

    popup.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="notification-text">
                    <p>${message}</p>
                    <a href="/notifications/show/${notification.id}" class="notification-link">
                        ${window.translations.notification.viewDetails}
                    </a>
                </div>
            </div>
            <button class="notification-close">&times;</button>
        `;

    // Close button
    popup.querySelector('.notification-close').addEventListener('click', () => {
    popup.remove();
});

    document.body.appendChild(popup);

    // Animate in
    setTimeout(() => {
    popup.classList.add('show');
}, 10);

    // Remove after 10s
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
    .catch(error => console.error('Error fetching unread notification count:', error));
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
    .catch(error => console.error('Error marking notification as read:', error));
}

    // Start intervals
    setInterval(updateNotificationCount, COUNT_POLL_INTERVAL);
    setInterval(checkNotifications, NOTIFICATION_POLL_INTERVAL);

    // Run once on load
    updateNotificationCount();
    checkNotifications();
});
