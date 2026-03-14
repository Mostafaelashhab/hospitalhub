const CACHE_NAME = 'hospital-pwa-v1';
const OFFLINE_URL = '/offline.html';

// Assets to cache on install
const PRECACHE_ASSETS = [
    '/',
    '/offline.html',
    '/favicon.svg',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
];

// Install - cache core assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate - clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        })
    );
    self.clients.claim();
});

// Fetch - network first, fallback to cache, then offline page
// iOS Safari requires careful handling of opaque responses and range requests
self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;

    // Skip cross-origin requests (iOS can be strict about these)
    if (!event.request.url.startsWith(self.location.origin)) return;

    // Skip API/auth/CSRF requests
    const url = new URL(event.request.url);
    if (
        url.pathname.startsWith('/api') ||
        url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/logout') ||
        url.pathname.startsWith('/sanctum') ||
        url.pathname.includes('csrf')
    ) return;

    // iOS Safari: skip range requests (audio/video)
    if (event.request.headers.get('range')) return;

    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Only cache valid responses (not opaque - iOS issue)
                if (response.ok && response.type === 'basic') {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request).then((cachedResponse) => {
                    if (cachedResponse) return cachedResponse;

                    // For navigation requests, show offline page
                    if (event.request.mode === 'navigate') {
                        return caches.match(OFFLINE_URL);
                    }

                    return new Response('Offline', {
                        status: 503,
                        statusText: 'Service Unavailable',
                    });
                });
            })
    );
});

// Push notification received
// iOS 16.4+ supports web push in standalone PWA mode
self.addEventListener('push', (event) => {
    let data = {
        title: 'Hospital System',
        body: 'You have a new notification',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-96x96.png',
        url: '/',
    };

    if (event.data) {
        try {
            const payload = event.data.json();
            data = { ...data, ...payload };
        } catch (e) {
            data.body = event.data.text();
        }
    }

    // iOS doesn't support notification actions, so we keep it simple
    const options = {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        data: { url: data.url },
        dir: 'auto',
    };

    // Only add vibrate and actions for non-iOS (they're ignored on iOS anyway)
    const isIOS = /iPad|iPhone|iPod/.test(self.navigator?.userAgent || '');
    if (!isIOS) {
        options.vibrate = [200, 100, 200];
        options.actions = [
            { action: 'open', title: 'Open' },
            { action: 'close', title: 'Close' },
        ];
    }

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'close') return;

    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((clientList) => {
            // Focus existing window if open
            for (const client of clientList) {
                if (client.url.includes(self.location.origin) && 'focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }
            // Open new window
            return clients.openWindow(url);
        })
    );
});
