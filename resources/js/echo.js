import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Initialize Echo only when a Vite env key is provided. This prevents
// the client-side Pusher error "You must pass your app key when you
// instantiate Pusher" if the VITE variables are missing during dev.
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY || import.meta.env.VITE_PUSHER_APP_KEY || null;

if (reverbKey) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
} else {
    // No key set — skip Echo initialization to avoid runtime errors.
    console.info('Echo not initialized: VITE_REVERB_APP_KEY / VITE_PUSHER_APP_KEY missing');
}
