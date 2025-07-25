import { createRouter, createWebHistory } from 'vue-router'
import Homepage from '../views/Homepage.vue'

const routes = [
    { path: '/', name: 'homepage', component: Homepage }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router