import api from './axios'

export const getComments = (ticketId) => {
  return api.get(`/tickets/${ticketId}/comments`)
}

export const createComment = (ticketId, payload) => {
  return api.post(`/tickets/${ticketId}/comments`, payload)
}