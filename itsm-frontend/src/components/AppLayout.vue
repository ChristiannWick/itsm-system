<template>
    <div class="flex h-screen bg-gray-100 ">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white p-4">
            <h2 class="text-xl font-bold mb-4">ITSM</h2>

            <nav class="space-y-2">
                <router-link
                    to="/"
                    class="block px-2 py-1 rounded hover:bg-gray-700"
                    >
                    Dashboard
                </router-link>
                <router-link
                    to="/tickets"
                    class="block px-2 py-1 rounded hover:bg-gray-700"
                    >
                    Tickets
                </router-link>
                
            </nav>
        </aside>

        <!-- Main Content -->

        <div class="flex-1 flex flex-col">
            <!-- Topbar -->
            <header class="bg-white shadow px-6 py-3 flex justify-between items-center">
                <span class="font-semibold">Dashboard</span>
                <button @click="logout" class="text-red-500">Logout</button>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-6">
                <router-view/>
            </main>
        </div>
    </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const logout = async () => {
    auth.logout()
    router.push('/login')
}
</script>