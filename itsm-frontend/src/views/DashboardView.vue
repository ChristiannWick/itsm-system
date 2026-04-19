<template>
  <div class="space-y-6">

    <h2 class="text-2xl font-bold">Dashboard</h2>

    <div class="grid grid-cols-4 gap-4">

      <div class="bg-white p-4 rounded-xl shadow">
        <p class="text-sm text-gray-500">Total Tickets</p>
        <p class="text-2xl font-bold">{{ stats.total }}</p>
      </div>

      <div class="bg-yellow-100 p-4 rounded-xl">
        <p class="text-sm">Open</p>
        <p class="text-2xl font-bold">{{ stats.open }}</p>
      </div>

      <div class="bg-blue-100 p-4 rounded-xl">
        <p class="text-sm">In Progress</p>
        <p class="text-2xl font-bold">{{ stats.in_progress }}</p>
      </div>

      <div class="bg-green-100 p-4 rounded-xl">
        <p class="text-sm">Resolved</p>
        <p class="text-2xl font-bold">{{ stats.resolved }}</p>
      </div>

      <div class="bg-red-100 p-4 rounded-xl col-span-4">
        <p class="text-sm">SLA Breached</p>
        <p class="text-2xl font-bold">{{ stats.breached }}</p>
      </div>

    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getDashboard } from '@/api/dashboard.api'

const stats = ref({})

const loadDashboard = async () => {
  const { data } = await getDashboard()
  stats.value = data
}

onMounted(loadDashboard)
</script>