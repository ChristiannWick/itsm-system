import api from '@/api/axios'

export const getTickets = (params = {}) => {
    return api.get('/tickets', { params })
}

export const createTicket = (payload) => {
    return api.post('/tickets', payload)
}