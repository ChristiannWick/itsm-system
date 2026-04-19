<template>
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Form -->
        <div class="bg-white p-4 rounded-xl shadow space-y-3">
            <h3 class="font-semibold text-lg">Create Ticket</h3>

            <form @submit.prevent="submit" class="space-y-3">

                <input 
                v-model="form.title" 
                placeholder="Title"
                class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                />

                <textarea 
                v-model="form.description"
                placeholder="Description"
                class="w-full border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                ></textarea>

                <select 
                v-model="form.category_id"
                class="w-full border rounded-lg p-2"
                >
                <option disabled value="">Select Category</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                </option>
                </select>

                <button 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
                    Create Ticket
                </button>

            </form>
        </div>
      

        <div class="bg-white p-4 rounded-xl shadow space-y-3">
            <h3 class="font-semibold text-lg">Tickets</h3>

            <div 
                v-for="ticket in tickets" 
                :key="ticket.id" 
                class="border rounded-lg p-4 flex justify-between items-center hover:shadow transition"
            >
                <div>
                <p class="font-medium text-gray-800">
                    {{ ticket.title }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ ticket.description }}
                </p>

                <div class="flex gap-2 mt-2">
                    <span 
                        class="text-xs px-2 py-1 rounded"
                        :class="statusClass(ticket.status)"
                        >
                        {{ ticket.status }}
                    </span>

                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded">
                    {{ ticket.priority }}
                    </span>
                </div>
                </div>

                <div class="space-x-2">
                <button @click="startEdit(ticket)" class="text-blue-500 text-sm">
                    Edit
                </button>
                <button @click="remove(ticket.id)" class="text-red-500 text-sm">
                    Delete
                </button>
                </div>
            </div>
            </div>

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

const statusClass = (status) => {
  return {
    open: 'bg-yellow-100 text-yellow-700',
    in_progress: 'bg-blue-100 text-blue-700',
    resolved: 'bg-green-100 text-green-700',
    closed: 'bg-gray-200 text-gray-700',
  }[status] || 'bg-gray-100'
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