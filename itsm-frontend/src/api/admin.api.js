import api from '@/api/axios'

export const getUsers = () => {
  return api.get('/admin/users')
}

export const updateUserRole = (id, role) => {
  return api.put(`/admin/users/${id}/role`, { role })
}

export const getAgents = ()=>{

    return api.get('/admin/users?role=agent')

}