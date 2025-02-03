// assets/app.js
import { createApp } from 'vue'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import App from './App.vue'
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

const app = createApp(App)
app.use(vuetify)
app.use(router)
app.mount('#app')
