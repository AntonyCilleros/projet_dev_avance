// assets/app.js
import { createApp } from 'vue'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import App from '@/vue/Root.vue'
import { createRouter, createWebHistory } from "vue-router";
import { routes } from './routes'
// Importez les styles de Vuetify
import 'vuetify/styles'

const router = createRouter({
    history: createWebHistory(),
    routes
})

const vuetify = createVuetify({
    components,
    directives,
})

const appElement = document.getElementById('app');
// pour chaque element dans appElement.dataset, on parse le JSON
const data = Object.keys(appElement.dataset).reduce((acc, key) => {
    acc[key] = JSON.parse(appElement.dataset[key]);
    return acc;
}, {});

const app = createApp(App, { data: data })
app.use(vuetify)
app.use(router)
app.mount('#app')

window.root = app._instance.proxy;
