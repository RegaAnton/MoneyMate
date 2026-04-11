const CACHE_NAME = "moneymate-laravel-v1";
const ASSETS_TO_CACHE = ["/", "/style.css", "/manifest.json"];

self.addEventListener("install", (event) => {
    self.skipWaiting();
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(ASSETS_TO_CACHE);
        }),
    );
    console.log("Service Worker: Terpasang (dengan Cache)");
});

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches
            .keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cache) => {
                        if (cache !== CACHE_NAME) return caches.delete(cache);
                    }),
                );
            })
            .then(() => self.clients.claim()),
    );
    console.log("Service Worker: Aktif (Cache siap)");
});

self.addEventListener("fetch", (event) => {
    if (event.request.url.includes("/livewire/")) {
        event.respondWith(fetch(event.request));
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then((networkResponse) => {
                return caches.open(CACHE_NAME).then((cache) => {
                    if (event.request.method === "GET") {
                        cache.put(event.request, networkResponse.clone());
                    }
                    return networkResponse;
                });
            })
            .catch(() => caches.match(event.request)),
    );
});
