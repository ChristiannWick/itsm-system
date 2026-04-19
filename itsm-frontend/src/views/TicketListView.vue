<template>
    <div>
        <h2>Tickets</h2>

        <!-- Create Ticket Form -->
        <form @submit.prevent="submit">
            <input v-model="form.title" placeholder="Title" />
            <textarea v-model="form.description" placeholder="Description"></textarea>
            
            <button>Create Ticket</button>
        </form>

        <hr>

        <!-- Ticket List -->
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
import { getTickets, createTicket } from '@/api/ticket.api'
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'

const tickets = ref([])
const form = ref({
    title: '',
    description: '',
})

const auth = useAuthStore()
const router = useRouter()

const loadTickets = async () => {
    const {data} = await getTickets()
    tickets.value = data.data
}

const submit = async () => {
    const { data } = await createTicket(form.value)

    console.log(data, 'data in submit')

    tickets.value.unshift(data)

    form.value.title = ''
    form.value.description = ''
}

const logout = () => {
    auth.logout()
    router.push('/login')
}

onMounted(() => {
    loadTickets()
})
</script>