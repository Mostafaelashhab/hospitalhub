const CACHE_NAME = 'hospital-pwa-v2';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
    '/',
    '/offline.html',
    '/favicon.svg',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

// Install
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => cache.addAll(PRECACHE_ASSETS))
    );
    self.skipWaiting();
});

// Activate
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((names) =>
            Promise.all(names.filter((n) => n !== CACHE_NAME).map((n) => caches.delete(n)))
        )
    );
    self.clients.claim();
});

// Fetch
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') return;
    if (!event.request.url.startsWith(self.location.origin)) return;

    const url = new URL(event.request.url);
    if (url.pathname.startsWith('/api') || url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/logout') || url.pathname.includes('csrf')) return;
    if (event.request.headers.get('range')) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                if (response.ok && response.type === 'basic') {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(event.request, clone));
                }
                return response;
            })
            .catch(() =>
                caches.match(event.request).then((cached) => {
                    if (cached) return cached;
                    if (event.request.mode === 'navigate') return caches.match(OFFLINE_URL);
                    return new Response('Offline', { status: 503 });
                })
            )
    );
});

// ===== PUSH - This is what iOS needs to work in background =====
self.addEventListener('push', (event) => {
    // iOS REQUIRES showing a notification immediately - no async delays
    let title = 'Hospital System';
    let body = 'You have a new notification';
    let icon = '/icons/icon-192x192.png';
    let badge = '/icons/icon-96x96.png';
    let url = '/';

    if (event.data) {
        try {
            const payload = event.data.json();
            title = payload.title || title;
            body = payload.body || body;
            icon = payload.icon || icon;
            badge = payload.badge || badge;
            url = payload.url || payload.data?.url || url;
        } catch (e) {
            body = event.data.text() || body;
        }
    }

    // Keep it SIMPLE for iOS - minimal options
    const promise = self.registration.showNotification(title, {
        body: body,
        icon: icon,
        badge: badge,
        tag: 'hospital-notification-' + Date.now(),
        renotify: true,
        data: { url: url },
    });

    event.waitUntil(promise);
});

// Notification click
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            for (const client of windowClients) {
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            return clients.openWindow(url);
        })
    );
});
