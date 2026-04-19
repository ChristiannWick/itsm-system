<template>
    <div>
        <h2>Tickets</h2>

        <button @click="loadTickets">Load Tickets</button>

        <ul>
            <li v-for="ticket in tickets" :key="ticket.id">
                {{ ticket.title }} - {{ ticket.status }}
            </li>
        </ul>

        <button @click="logout">Logout</button>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import api from'@/api/axios'
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'

const tickets = ref([])

const loadTickets = async () => {
    const {data} = await api.get('/tickets')
    tickets.value = data.data
}

const auth = useAuthStore()
const router = useRouter()

const logout = () => {
    auth.logout()
    router.push('/login')
}

onMounted(() => {
    loadTickets()
})
</script>