import api from '@/api/axios'

export const getActivities = (ticketId)=>{

 return api.get(

  `/tickets/${ticketId}/activities`

 )

}