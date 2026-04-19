import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView.vue'
import TicketListView from '@/views/TicketListView.vue'

const routes = [
    { path: '/login', component: LoginView },
    { path: '/tickets', component: TicketListView },
]


export default createRouter({
    history: createWebHistory(),
    routes,
})