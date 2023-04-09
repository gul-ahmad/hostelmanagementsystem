import axios from '@axios'
import { defineStore } from 'pinia'

export const useUserListStore = defineStore('UserListStore', {
  actions: {
    // 👉 Fetch users data
    fetchUsers(params) { return axios.get('api/user-list', { params }) },

    // fetchUsers(params) {
    //   //alert('mdmfdm')
      
    //   return new Promise((resolve, reject) => {
    //     axios.get('api/user-list', { params })
    //       .then(response => {
    //         console.log(response.data)
    //         resolve(response)
    //       })
    //       .catch(error => reject(error))
    //   })
    // },

    // 👉 Add User
    addUser(userData) {
      return new Promise((resolve, reject) => {
        axios.post('/apps/users/user', {
          user: userData,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    // 👉 fetch single user
    fetchUser(id) {
      return new Promise((resolve, reject) => {
        axios.get(`/api/user-list/${id}`).then(response => resolve(response)).catch(error => reject(error))
      })
    },
  },
})
