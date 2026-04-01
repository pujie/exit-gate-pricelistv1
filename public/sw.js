const CACHE_NAME = "pricelist-v1";
const assetsToCache = [
    "/",
    "/css/app.css",
    "/js/app.js",
    "/offline", // Halaman khusus saat benar-benar tidak ada sinyal
];

self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(assetsToCache);
        })
    );
});

self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request);
        })
    );
});