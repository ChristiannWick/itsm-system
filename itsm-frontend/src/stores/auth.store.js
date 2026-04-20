import { defineStore } from "pinia";
import api from '@/api/axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        // user: null,
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null
    }),
    
    actions: {
        async login(credentials) {
            // const { data } = await api.post('/login', credentials)
            const response = await api.post('/login', credentials)
            console.log(response.data.user,'data in login')

            const token = response.data.token
            const user = response.data.user

            this.token = token
            this.user = user
            

            localStorage.setItem('token', token)
            localStorage.setItem('user', JSON.stringify(user))
        },

        logout() {
            localStorage.removeItem('token')
            localStorage.removeItem('user')
            
            this.token = null
            this.user = null
        }
    }
})