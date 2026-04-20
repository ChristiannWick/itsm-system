<template>

<div class="max-w-3xl mx-auto space-y-6">

<h2 class="text-xl font-bold">

Categories

</h2>


<div class="bg-white p-4 rounded shadow space-y-3">

<input
v-model="name"
placeholder="New category"
class="border p-2 rounded w-full"
/>


<button
@click="createCategory"
class="bg-blue-500 text-white px-3 py-1 rounded"
>

Add

</button>

</div>



<div class="bg-white rounded shadow">

<div
v-for="c in categories"
:key="c.id"
class="flex justify-between border-b p-3"
>

{{ c.name }}

<button
@click="remove(c.id)"
class="text-red-500"
>

delete

</button>

</div>

</div>


</div>

</template>



<script setup>

import { ref,onMounted } from 'vue'
import api from '@/api/axios'

const categories = ref([])

const name = ref('')


const loadCategories = async ()=>{

 const {data} = await api.get('/admin/categories')

 categories.value = data

}



const createCategory = async ()=>{

 const {data} = await api.post('/admin/categories',{
  name:name.value
 })

 categories.value.push(data)

 name.value=''

}



const remove = async(id)=>{

 await api.delete(`/admin/categories/${id}`)

 categories.value = categories.value.filter(c=>c.id!==id)

}



onMounted(loadCategories)

</script>