import axios from 'axios'
import { useAuthStore } from '@/stores/auth.store'

const api = axios.create({
    baseURL: 'http://localhost:8000/api/v1',
    headers: { Accept: 'application/json' },
})


api.interceptors.request.use(config => {
    const auth = useAuthStore()
    if(auth.token) {
        config.headers.Authorization = `Bearer ${auth.token}`
    }
    return config
})

export default api