const CACHE_NAME = "moneymate-cache-v1";
const ASSETS_TO_CACHE = [
  "/",
  "/index.html",
  "/style.css",
  "/app.js",
  "/manifest.json",
];

// Install Service Worker dan simpan file ke Cache
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log("Service Worker: Memasukkan aset ke cache");
      return cache.addAll(ASSETS_TO_CACHE);
    }),
  );
});

// Bersihkan Cache lama jika ada versi baru
self.addEventListener("activate", (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log("Service Worker: Menghapus cache lama", cache);
            return caches.delete(cache);
          }
        }),
      );
    }),
  );
});

// Ambil file dari Cache jika ada, jika tidak ambil dari Internet
self.addEventListener("fetch", (event) => {
  // JANGAN cache request yang mengarah ke API/Database (biarkan ambil langsung dari server)
  if (event.request.url.includes("/api/")) {
    event.respondWith(fetch(event.request));
    return;
  }

  // Untuk file tampilan (HTML/CSS/JS), prioritaskan Cache
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      return cachedResponse || fetch(event.request);
    }),
  );
});
