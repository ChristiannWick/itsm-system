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
                <div class="relative">

                    <button class="relative">
                        🔔
                        <span 
                        v-if="notifications.filter(n => !n.read_at).length"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1 rounded-full"
                        >
                        {{ notifications.filter(n => !n.read_at).length }}
                        </span>
                    </button>

                    <div class="absolute right-0 mt-2 w-64 bg-white shadow rounded p-2">
                        
                        <div 
                        v-for="n in notifications" 
                        :key="n.id"
                        class="p-2 border-b text-sm cursor-pointer"
                        @click="markRead(n)"
                        >
                        {{ n.data.message }}
                        </div>

                    </div>

                </div>
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
import { ref, onMounted } from 'vue'
import { getNotifications, markAsRead } from '@/api/notification.api'

const notifications = ref([])

const auth = useAuthStore()
const router = useRouter()

const logout = async () => {
    auth.logout()
    router.push('/login')
}

const loadNotifications = async () => {
  const { data } = await getNotifications()
  notifications.value = data
}

const markRead = async (n) => {
  await markAsRead(n.id)
  n.read_at = new Date()
}

onMounted(() => {
  loadNotifications()

  setInterval(() => {
    loadNotifications()
  }, 10000) // every 10s
})
</script>