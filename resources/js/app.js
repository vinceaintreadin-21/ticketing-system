import './bootstrap';

window.Echo.channel('test')
    .listen('TestEvent', (e) => {
        console.log('Event received:', e.message);
    });
