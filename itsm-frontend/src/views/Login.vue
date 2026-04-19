<template>
    <div>
        <h2>Login</h2>
        <form @submit.prevent="submit">
            <input v-model="email" placeholder="Email"/>
            <input v-model="password" placeholder="Password">
            <button>Login</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth.store';
import { useRouter } from 'vue-router'

const email = ref('')
const password = ref('')
const auth = useAuthStore()
const router = useRouter()

const submit = async () => {
    await auth.login({
        email: email.value,
        password: password.value,
    })

    router.push('/tickets')
}
</script>