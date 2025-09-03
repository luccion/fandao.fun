
const cacheName = 'fandao'
const files = [
    '/view/js/jquery.min.js',
    '/view/js/bootstrap.bundle.min.js',
    '/view/css/all.min.css',
    '/view/css/bootstrap.min.css',
]
self.addEventListener('install', e => {
    e.waitUntil(
        (async () => {
            const cache = await caches.open(cacheName)
            await cache.addAll(files)
        })()
    )
})
self.addEventListener('fetch', e => {
    e.respondWith(caches.match(e.request).then(response => response || fetch(e.request)))
})
self.addEventListener('activate', function (e) {
    e.waitUntil(
        caches.keys().then(function (keyList) {
            return Promise.all(
                keyList.map(function (key) {

                    if (cacheName !== key) {
                        return caches.delete(key)
                    }
                })
            )
        })
    )
})
