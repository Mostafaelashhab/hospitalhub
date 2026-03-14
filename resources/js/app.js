import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// ===== PWA Service Worker Registration =====
if ('serviceWorker' in navigator) {
    window.addEventListener('load', async () => {
        try {
            const registration = await navigator.serviceWorker.register('/sw.js');
            console.log('SW registered:', registration.scope);

            // Wait for the service worker to be ready
            const swReg = await navigator.serviceWorker.ready;
            window.swRegistration = swReg;
            console.log('SW ready:', swReg.scope);

            // If already granted, auto-subscribe
            if ('PushManager' in window && Notification.permission === 'granted') {
                await subscribeToPush();
            }
        } catch (error) {
            console.log('SW registration failed:', error);
        }
    });
}

// Function to request push notification permission (call from UI)
window.requestPushPermission = async function () {
    if (!('Notification' in window) || !('PushManager' in window)) {
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

        if (isIOS && !isStandalone) {
            alert('لتفعيل الإشعارات، قم بإضافة التطبيق للشاشة الرئيسية أولاً.\n\nTo enable notifications, add this app to your Home Screen first.');
        } else {
            alert('This browser does not support push notifications.');
        }
        return false;
    }

    try {
        const permission = await Notification.requestPermission();
        console.log('Notification permission:', permission);

        if (permission === 'granted') {
            const success = await subscribeToPush();
            return success;
        }
    } catch (error) {
        console.log('Permission request failed:', error);
    }

    return false;
};

async function subscribeToPush() {
    try {
        // Wait for SW to be ready
        let registration = window.swRegistration;
        if (!registration) {
            registration = await navigator.serviceWorker.ready;
            window.swRegistration = registration;
        }

        // Check if already subscribed
        let subscription = await registration.pushManager.getSubscription();
        console.log('Existing subscription:', subscription ? 'yes' : 'no');

        if (!subscription) {
            // Get VAPID public key from meta tag
            const vapidMeta = document.querySelector('meta[name="vapid-public-key"]');
            if (!vapidMeta || !vapidMeta.content) {
                console.log('VAPID public key not found in meta tag.');
                return false;
            }

            const vapidPublicKey = vapidMeta.content;
            console.log('VAPID key found, subscribing...');
            const convertedKey = urlBase64ToUint8Array(vapidPublicKey);

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedKey,
            });
            console.log('Push subscription created:', subscription.endpoint);
        }

        // Send subscription to server
        await sendSubscriptionToServer(subscription);
        return true;
    } catch (error) {
        console.error('Push subscription failed:', error);
        return false;
    }
}

async function sendSubscriptionToServer(subscription) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const subJSON = subscription.toJSON();

    console.log('Sending subscription to server...');
    console.log('Endpoint:', subJSON.endpoint);
    console.log('Keys:', subJSON.keys ? 'present' : 'missing');

    try {
        const response = await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({
                endpoint: subJSON.endpoint,
                keys: {
                    p256dh: subJSON.keys.p256dh,
                    auth: subJSON.keys.auth,
                },
            }),
        });

        if (response.ok) {
            console.log('Push subscription saved to server!');
        } else {
            const text = await response.text();
            console.error('Server error:', response.status, text);
        }
    } catch (error) {
        console.error('Failed to send subscription to server:', error);
    }
}

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - (base64String.length % 4)) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
