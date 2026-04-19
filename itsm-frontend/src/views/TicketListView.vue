<template>
    <div>
        <h2>Tickets</h2>

        <!-- Create Ticket Form -->
        <form @submit.prevent="submit">
            <input v-model="form.title" placeholder="Title" />
            <textarea v-model="form.description" placeholder="Description"></textarea>
            <select v-model="form.category_id">
                <option disabled value="">Select Category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
            </select>
            
            <button>Create Ticket</button>
        </form>

        <hr>

        <!-- Ticket List -->
        <ul>
            <li v-for="ticket in tickets" :key="ticket.id">
                {{ ticket.title }} - {{ ticket.status }} - {{ ticket.category }}
            </li>
        </ul>

        <button @click="logout">Logout</button>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { getTickets, createTicket } from '@/api/ticket.api'
import { getCategories } from '@/api/category.api'
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()

const tickets = ref([])
const categories = ref([])
const form = ref({
    title: '',
    description: '',
    category_id: null,
})
const loading = ref(false)


const loadTickets = async () => {
    const {data} = await getTickets()
    tickets.value = data.data
}

const submit = async () => {
    loading.value = true
    const { data } = await createTicket(form.value)
    loading.value = false
    console.log(data, 'data in submit')

    tickets.value.unshift(data)

    form.value.title = ''
    form.value.description = ''
}

const loadCategories = async () => {
    const { data } = await getCategories()
    categories.value = data
}

const logout = () => {
    auth.logout()
    router.push('/login')
}

onMounted(() => {
    loadTickets()
    loadCategories()
})
</script>