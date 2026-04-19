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

                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                        {{ ticket.priority }}
                    </span>

                    <!-- SLA -->
                    <span 
                        class="text-xs px-2 py-1 rounded"
                        :class="slaClass(ticket.sla_due_at)"
                    >
                        ⏱ {{ getRemainingTime(ticket.sla_due_at) }}
                    </span>
                </div>

                <div class="mt-4">

                    <button 
                        @click="loadComments(ticket.id)" 
                        class="text-sm text-blue-500"
                    >
                        Load Comments
                    </button>

                    <div v-if="comments[ticket.id]" class="mt-2 space-y-2">

                        <div 
                        v-for="c in comments[ticket.id]" 
                        :key="c.id"
                        class="bg-gray-100 p-2 rounded text-sm"
                        >
                        <strong>{{ c.user.name }}</strong>: {{ c.content }}
                        </div>

                        <div class="flex gap-2 mt-2">
                        <input 
                            v-model="commentInput[ticket.id]" 
                            placeholder="Write a comment..."
                            class="flex-1 border p-1 rounded"
                        />

                        <button 
                            @click="submitComment(ticket.id)"
                            class="bg-blue-500 text-white px-2 rounded"
                        >
                            Send
                        </button>
                        </div>

                    </div>
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
import { ref, onMounted, onUnmounted  } from 'vue';
import { getTickets, createTicket, updateTicket, deleteTicket } from '@/api/ticket.api'
import { getCategories } from '@/api/category.api'
import { useAuthStore } from '@/stores/auth.store'
import { useRouter } from 'vue-router'
import { getComments, createComment } from '@/api/comment.api'

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


// comments
const comments = ref({})
const commentInput = ref({})

const loadComments = async (ticketId) => {
  const { data } = await getComments(ticketId)
  comments.value[ticketId] = data
}

const submitComment = async (ticketId) => {
  const { data } = await createComment(ticketId, {
    content: commentInput.value[ticketId],
  })

  comments.value[ticketId].unshift(data)
  commentInput.value[ticketId] = ''
}


//SLA
const getRemainingTime = (due) => {
  const diff = new Date(due) - new Date()

  if (diff <= 0) return 'BREACHED'

  const hours = Math.floor(diff / (1000 * 60 * 60))
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))

  return `${hours}h ${minutes}m`
}

const slaClass = (due) => {
  const diff = new Date(due) - new Date()
  const hours = diff / (1000 * 60 * 60)

  if (diff <= 0) return 'bg-red-100 text-red-700'
  if (hours < 4) return 'bg-orange-100 text-orange-700'
  if (hours < 12) return 'bg-yellow-100 text-yellow-700'

  return 'bg-green-100 text-green-700'
}

const logout = () => {
    auth.logout()
    router.push('/login')
}

let interval = null
onMounted(() => {
    loadTickets()
    loadCategories()

    interval = setInterval(() => {
        tickets.value = [...tickets.value] // force re-render
    }, 60000) // update every minute
})

onUnmounted(() => {
  clearInterval(interval)
})
</script>