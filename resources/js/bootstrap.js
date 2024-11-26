import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Setup Echo
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',   // or 'socket.io' or 'null' for no broadcasting
    key: 'your-pusher-key',
    cluster: 'your-cluster', // Leave empty if you're not using Pusher
    forceTLS: true,
});

// Listen to the channel
Echo.channel('notifications')
    .listen('NewRequestNotification', (event) => {
        console.log('New Request:', event);
    });

