navigator.serviceWorker.register('service-worker.js').then(registration => {
  registration.pushManager.subscribe({userVisibleOnly: true}).then(subscription => {
    console.log(subscription.endpoint);
  })
})
self.addEventListener('push', event => {
  event.waitUntil(
    self.registration.showNotification('CromoHelp Notificaciones')
  );
});