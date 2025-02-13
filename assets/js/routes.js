import Home from '@/vue/Home.vue'
import Login from '@/vue/Login.vue'
import Emby from '@/vue/Emby.vue'

export const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/emby',
        name: 'Emby',
        component: Emby,
        meta: { requiresAuth: true } // Nécessite une authentification
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: { name: 'Home' }
    }
]

