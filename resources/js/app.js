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

            // Check for push notification support
            // iOS 16.4+ supports push only in standalone (installed) PWA mode
            if ('PushManager' in window) {
                await initPushNotifications(registration);
            }
        } catch (error) {
            console.log('SW registration failed:', error);
        }
    });
}

// Detect iOS
function isIOS() {
    return /iPad|iPhone|iPod/.test(navigator.userAgent) ||
        (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
}

// Check if running as installed PWA (standalone mode)
function isStandalone() {
    return window.matchMedia('(display-mode: standalone)').matches ||
        window.navigator.standalone === true;
}

async function initPushNotifications(registration) {
    const permission = Notification.permission;

    if (permission === 'denied') {
        console.log('Push notifications are blocked by user.');
        return;
    }

    // Store registration globally so we can use it later
    window.swRegistration = registration;

    // On iOS, push only works in standalone PWA mode
    if (isIOS() && !isStandalone()) {
        console.log('iOS: Push notifications require the app to be installed (Add to Home Screen).');
        window.iosPushRequiresInstall = true;
    }
}

// Function to request push notification permission (call from UI)
window.requestPushPermission = async function () {
    if (!('Notification' in window)) {
        if (isIOS() && !isStandalone()) {
            alert('لتفعيل الإشعارات، قم بإضافة التطبيق للشاشة الرئيسية أولاً.\n\nTo enable notifications, add this app to your Home Screen first.');
        } else {
            alert('This browser does not support notifications.');
        }
        return false;
    }

    const permission = await Notification.requestPermission();

    if (permission === 'granted') {
        await subscribeToPush();
        return true;
    }

    return false;
};

async function subscribeToPush() {
    try {
        const registration = window.swRegistration;
        if (!registration) return;

        // Check if already subscribed
        let subscription = await registration.pushManager.getSubscription();

        if (!subscription) {
            // Get VAPID public key from meta tag
            const vapidMeta = document.querySelector('meta[name="vapid-public-key"]');
            if (!vapidMeta) {
                console.log('VAPID public key not found. Push disabled.');
                return;
            }

            const vapidPublicKey = vapidMeta.content;
            const convertedKey = urlBase64ToUint8Array(vapidPublicKey);

            subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedKey,
            });
        }

        // Send subscription to server
        await sendSubscriptionToServer(subscription);
    } catch (error) {
        console.log('Push subscription failed:', error);
    }
}

async function sendSubscriptionToServer(subscription) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    try {
        await fetch('/push/subscribe', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(subscription),
        });
        console.log('Push subscription sent to server.');
    } catch (error) {
        console.log('Failed to send subscription to server:', error);
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

// ===== iOS Install Prompt Helper =====
// Show a custom "Add to Home Screen" prompt for iOS users
window.showIOSInstallPrompt = function () {
    if (!isIOS() || isStandalone()) return false;

    // Return true so the UI can show a banner/modal
    return true;
};
