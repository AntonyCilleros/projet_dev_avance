import Home from './vue/Home.vue'
import Login from './vue/Login.vue'

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
    }
]

