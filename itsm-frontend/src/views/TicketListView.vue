<template>
    <div>
        <h2>Tickets</h2>

        <button @click="loadTickets">Load Tickets</button>

        <ul>
            <li v-for="ticket in tickets" :key="ticket.id">
                {{ ticket.title }} - {{ ticket.status }}
            </li>
        </ul>
    </div>
</template>


<script setup>
import { ref } from 'vue';
import api from'@/api/axios'

const tickets = ref([])

const loadTickets = async () => {
    const {data} = await api.get('/tickets')
    tickets.value = data.data
}
</script>