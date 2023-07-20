import axios from '@axios'
import { defineStore } from 'pinia'

export const useRoomListStore = defineStore('RoomListStore', {
  state: () => ({
    tags: [],
    pondimages:[],
  }),
  actions: {
    // 👉 Fetch users data
    fetchRooms(params) {
      //console.log(params) // log the incoming params object
      // console.log(localStorage.getItem('accessToken'))
      
      return axios.get('/api/rooms/frontend', { params })
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
    fetchImagesUploadedByFilePond() {
      return axios.get('api/auth/tempimages')
        .then(response => {
          this.pondimages = response.data
          
          return response.data
        })
        .catch(error => {
          console.error(error)
        })
    },

  
    // 👉 fetch single Room
    fetchUser(id) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/auth/room/${id}`).then(response => resolve(response)).catch(error => reject(error))
      })
    },
  },
})
