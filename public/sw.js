/**
 * Everest Service Worker
 * 
 * Gerencia Web Push Notifications.
 * Preparado para implementação futura - requer VAPID configurado no .env
 */

// Evento de instalação
self.addEventListener('install', (event) => {
    console.log('[SW] Service Worker installed');
    self.skipWaiting();
});

// Evento de ativação
self.addEventListener('activate', (event) => {
    console.log('[SW] Service Worker activated');
    event.waitUntil(self.clients.claim());
});

// Evento de push notification
self.addEventListener('push', (event) => {
    console.log('[SW] Push received');

    if (!event.data) {
        console.log('[SW] Push event but no data');
        return;
    }

    const data = event.data.json();

    const options = {
        body: data.body || 'Sua ofensiva está em risco!',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/',
            dateOfArrival: Date.now(),
        },
        actions: [
            {
                action: 'open',
                title: 'Abrir Everest',
            },
            {
                action: 'dismiss',
                title: 'Dispensar',
            },
        ],
        tag: data.tag || 'streak-reminder',
        renotify: true,
    };

    event.waitUntil(
        self.registration.showNotification(data.title || '🔥 Ofensiva em Risco!', options)
    );
});

// Evento de clique na notificação
self.addEventListener('notificationclick', (event) => {
    console.log('[SW] Notification clicked');

    event.notification.close();

    if (event.action === 'dismiss') {
        return;
    }

    const urlToOpen = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then((clientList) => {
                // Se já tem uma janela aberta, foca nela
                for (const client of clientList) {
                    if (client.url.includes(self.location.origin) && 'focus' in client) {
                        client.navigate(urlToOpen);
                        return client.focus();
                    }
                }
                // Se não, abre uma nova
                if (clients.openWindow) {
                    return clients.openWindow(urlToOpen);
                }
            })
    );
});
