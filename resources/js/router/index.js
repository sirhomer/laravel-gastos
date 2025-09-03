import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import gastos from '../components/Gastos/index.vue';
import IngresosAdd from '../components/ingresos/add.vue';

const routes = [
    { path: '/', redirect: '/home' },
    { path: '/home', component: Home },
    { path: '/gastos', component: gastos },
    { path: '/ingresos/add', component: IngresosAdd }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;