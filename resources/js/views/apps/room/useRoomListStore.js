import axios from '@axios'
import { defineStore } from 'pinia'

export const useRoomListStore = defineStore('RoomListStore', {
  state: () => ({
    tags: [],
  }),
  actions: {
    // 👉 Fetch users data
    fetchRooms(params) {
      // console.log(params) // log the incoming params object
      console.log(localStorage.getItem('accessToken'))
      
      return axios.get('api/auth/rooms', { params })
    },

    fetchTags() {
      return axios.get('api/tags')
        .then(response => {
          this.tags = response.data
          
          return response.data
        })
        .catch(error => {
          console.error(error)
        })
    },

    // 👉 Add Room
    addRoom(roomData) {
      return new Promise((resolve, reject) => {
        axios.post('api/auth/rooms',roomData).then(response => {
          // console.log(response.data)

          const successMessage = {
           
            text: response.data.message,
            type: 'success',
          }

          resolve(successMessage)
        }).catch( error =>reject(error))
      })
    },

    editUser(userData){

      console.log(userData.name)

      //console.log('function in Userliststore>>>>>>called')

      return new Promise((resolve,reject)=>{

        axios.patch(`/api/auth/user/update/${userData.id}`,{
          user:{
            name:userData.name,
          },
        }).then(response => {
          const successMessage = {
            text: response.data.success,
            type: 'success',
          }

          resolve(successMessage)
        }).catch( error =>reject(error))
      })
        
      
    },

    userDeletedRecord(userData){
      if(userData){

        return new Promise((resolve,reject)=>{

          axios.delete(`/api/auth/user/delete/${userData}`,{
            user:{
              id:userData,
            },
          }).then(response => {
            const successMessage = {
              text: response.data.success,
              type: 'success',
            }

            resolve(successMessage)
          }).catch( error =>reject(error))
        })

      }
      else{
        console.log('cancelled')

      }
      



    },

    // 👉 fetch single Room
    fetchUser(id) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/auth/room/${id}`).then(response => resolve(response)).catch(error => reject(error))
      })
    },
  },
})
