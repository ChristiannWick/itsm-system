import api from '@/api/axios'

export const getUsers = () => {
  return api.get('/admin/users')
}

export const updateUserRole = (id, role) => {
  return api.put(`/admin/users/${id}/role`, { role })
}