<template>

<div class="max-w-5xl mx-auto space-y-6">

  <h2 class="text-2xl font-bold">
    User Management
  </h2>


  <div class="bg-white rounded-xl shadow overflow-hidden">

    <table class="w-full text-sm">

      <thead class="bg-gray-100">

        <tr>

          <th class="text-left p-3">Name</th>

          <th class="text-left p-3">Email</th>

          <th class="text-left p-3">Role</th>

        </tr>

      </thead>



      <tbody>

        <tr
          v-for="u in users"
          :key="u.id"
          class="border-t hover:bg-gray-50 transition"
        >

          <td class="p-3 font-medium text-gray-800">

            {{ u.name }}

          </td>



          <td class="p-3 text-gray-600">

            {{ u.email }}

          </td>



          <td class="p-3">

            <div class="flex items-center gap-2">

              <!-- role badge -->

              <span
                class="text-xs px-2 py-1 rounded"
                :class="roleBadge(u.role)"
              >

                {{ u.role }}

              </span>



              <!-- dropdown -->

              <select
                :value="u.role"
                @change="changeRole(u,$event)"
                class="border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-blue-400"
              >

                <option value="user">User</option>

                <option value="agent">Agent</option>

                <option value="admin">Admin</option>

              </select>

            </div>

          </td>

        </tr>

      </tbody>

    </table>

  </div>

</div>

</template>



<script setup>

import { ref,onMounted } from 'vue'
import api from '@/api/axios'

const users = ref([])



const loadUsers = async ()=>{

 const {data} = await api.get('/admin/users')

 users.value = data

}



const changeRole = async (user,event)=>{

 const role = event.target.value

 await api.put(`/admin/users/${user.id}/role`,{
  role
 })

 user.role = role

}



const roleBadge = (role)=>{

 return {

  admin:'bg-purple-100 text-purple-700',

  agent:'bg-blue-100 text-blue-700',

  user:'bg-gray-100 text-gray-700'

 }[role]

}



onMounted(loadUsers)

</script>