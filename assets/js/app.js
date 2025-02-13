// assets/app.js
import { createApp } from 'vue'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import App from '@/vue/Root.vue'
import { createRouter, createWebHistory } from "vue-router";
import { routes } from './routes'
import i18n from './i18n'
// Importez les styles de Vuetify
import 'vuetify/styles'
import axios from "axios";
import {printError} from "@/js/utils";


const appElement = document.getElementById('app');
// pour chaque element dans appElement.dataset, on parse le JSON
const data = Object.keys(appElement.dataset).reduce((acc, key) => {
    if (JSON.parse(appElement.dataset[key]).data)
        acc[key] = JSON.parse(appElement.dataset[key]).data;
    return acc;
}, {});

const vuetify = createVuetify({
    components,
    directives,
})

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.meta.requiresAuth) {
        let isConnected = false;
        axios.get('/api/connection/get_user').then(response => {
            isConnected = !!response.data.data;
            console.log("isConnected", isConnected);
            if (!!isConnected) {
                next(); // ✅ Autorise la navigation
            } else {
                next({ name: 'Login' }); // ❌ Redirige vers la page de connexion
            }
        }).catch(error => {
            printError("get_user", error);
            next({ name: 'Login' }); // ❌ Redirige vers la page de connexion
        })
    } else {
        next(); // ✅ Autorise la navigation
    }
});

const app = createApp(App, { data: data })
app.use(vuetify)
app.use(router)
app.use(i18n);
app.mount('#app')

window.root = app._instance.proxy;
