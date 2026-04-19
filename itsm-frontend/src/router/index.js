import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

import LoginView from '@/views/Login.vue'
import TicketListView from '@/views/TicketListView.vue'
import DashboardView from '@/views/DashboardView.vue'
import AppLayout from '../components/AppLayout.vue'

const routes = [
    { path: '/login', component: LoginView },
    // { path: '/tickets', component: TicketListView, meta: {requiresAuth: true} },
    {
        path: '/',
        component: AppLayout,
        children: [
             { path: '', component: DashboardView }, // 👈 default page
            { 
                path: 'tickets', 
                component: TicketListView, 
                meta: {requiresAuth: true} 
            }
        ]
    }
]


const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to) => {
    const auth = useAuthStore()

    if (to.meta.requiresAuth && !auth.token) {
        return '/login'
    }

    if (to.path === 'login' && auth.token) {
        return '/tickets'
    }
})

export default router