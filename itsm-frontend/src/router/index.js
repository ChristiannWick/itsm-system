import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth.store'

import LoginView from '@/views/Login.vue'
import TicketListView from '@/views/TicketListView.vue'
import DashboardView from '@/views/DashboardView.vue'
import AppLayout from '../components/AppLayout.vue'
import AdminUsersView from '@/views/admin/AdminUsersView.vue'
import AdminCategoriesView from '@/views/admin/AdminCategoriesView.vue'

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
            },
            {
                path:'admin/users',
                component:AdminUsersView
            },
            {
                path:'admin/categories',
                component:AdminCategoriesView
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
    console.log(auth.token,'token in index.js')

    if (to.meta.requiresAuth && !auth.token) {
        return '/login'
    }

    if (to.path === 'login' && auth.token) {
        return '/tickets'
    }
})

export default router