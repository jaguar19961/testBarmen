try {
    window.axios = require('axios');

    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
} catch (e) {
    console.error('Cannot load application');
}
