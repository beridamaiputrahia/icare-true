// I Care True — Service Worker v1.0
const CACHE_NAME = 'icaretrue-v1';
const OFFLINE_URL = '/offline';

// Static assets to pre-cache
const PRECACHE_ASSETS = [
    '/',
    '/offline',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
];

// Install: pre-cache static assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(PRECACHE_ASSETS).catch(() => {
                // Ignore cache failures for CDN resources
                return Promise.resolve();
            });
        }).then(() => self.skipWaiting())
    );
});

// Activate: clean old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        ).then(() => self.clients.claim())
    );
});

// Fetch: network-first with offline fallback
self.addEventListener('fetch', event => {
    // Skip non-GET, cross-origin, and browser-extension requests
    if (event.request.method !== 'GET') return;
    if (!event.request.url.startsWith(self.location.origin) &&
        !event.request.url.startsWith('https://cdn.jsdelivr.net') &&
        !event.request.url.startsWith('https://cdnjs.cloudflare.com')) return;

    // Skip POST/API/form requests
    const url = new URL(event.request.url);
    if (url.pathname.includes('/api/') || url.pathname.includes('/_debugbar/')) return;

    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Cache successful responses for CDN resources
                if (response.ok && (
                    event.request.url.includes('cdn.jsdelivr.net') ||
                    event.request.url.includes('cdnjs.cloudflare.com') ||
                    event.request.url.includes('/icons/')
                )) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(c => c.put(event.request, clone));
                }
                return response;
            })
            .catch(() =>
                caches.match(event.request).then(cached => {
                    if (cached) return cached;
                    // Show offline page for navigation requests
                    if (event.request.mode === 'navigate') {
                        return caches.match(OFFLINE_URL);
                    }
                    return new Response('', { status: 503 });
                })
            )
    );
});

// Push notification handler (future enhancement)
self.addEventListener('push', event => {
    if (!event.data) return;
    const data = event.data.json();
    event.waitUntil(
        self.registration.showNotification(data.title, {
            body:  data.body,
            icon:  '/icons/icon-192.png',
            badge: '/icons/icon-72.png',
            data:  { url: data.url },
        })
    );
});

self.addEventListener('notificationclick', event => {
    event.notification.close();
    if (event.notification.data?.url) {
        event.waitUntil(clients.openWindow(event.notification.data.url));
    }
});
