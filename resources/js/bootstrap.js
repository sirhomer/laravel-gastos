import axios from 'axios';
import * as bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.axios = axios;
window.bootstrap = bootstrap;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
