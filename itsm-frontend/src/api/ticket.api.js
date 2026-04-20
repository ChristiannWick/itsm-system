import api from '@/api/axios'

export const getTickets = (params = {}) => {
    return api.get('/tickets', { params })
}

export const createTicket = (payload) => {
    return api.post('/tickets', payload)
}

export const updateTicket = (id, payload) => {
    return api.put(`/tickets/${id}`, payload)
}

export const deleteTicket = (id) => {
    return api.delete(`/tickets/${id}`)
}

export const assignTicket = (ticketId,userId)=>{

    return api.post(`/tickets/${ticketId}/assign`, {
    
            assigned_to:userId
        }
    )

}