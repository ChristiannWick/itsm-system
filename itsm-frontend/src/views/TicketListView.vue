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
                <div v-if="editingId !== ticket.id">
                    {{ ticket.title }} - {{ ticket.description }} - {{ ticket.status }} - {{ ticket.category }}

                    <button @click="startEdit(ticket)">Edit</button>
                    <button @click="remove(ticket.id)">Delete</button>
                </div>

                <div v-else>
                    <input v-model="form.title" />
                    <textarea v-model="form.description"></textarea>
                    <select v-model="form.category_id">
                        <option disabled value="">Select Category</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>

                    <button @click="submitEdit">Save</button>
                    <button @click="editingId = null">Cancel</button>
                </div>
            </li>
        </ul>

        <button @click="logout">Logout</button>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue';
import { getTickets, createTicket, updateTicket, deleteTicket } from '@/api/ticket.api'
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
const editingId = ref(null)


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

const startEdit = (ticket) => {
    editingId.value = ticket.id

    form.value = {
        title: ticket.title,
        description: ticket.description,
        category_id: ticket.category_id
    }
    console.log(form.value, 'form.value at startEdit()')
}

const submitEdit = async () => {
    const { data } = await updateTicket(editingId.value, form.value)

    // update UI
    const index = tickets.value.findIndex(t => t.id === editingId.value)
    tickets.value[index] = data.data

    editingId.value = null

    form.value = {
        title: '',
        description: '',
        category_id: null,
    }
}

const remove = async (id) => {
    await deleteTicket(id)

    tickets.value = tickets.value.filter(t => t.id !== id)
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