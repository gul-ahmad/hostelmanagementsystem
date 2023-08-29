import axios from '@axios'
import { defineStore } from 'pinia'

export const useUserListStore = defineStore('UserListStore', {
//used state to define userRoles as array and it will be avialble in every 
//component
  state:()=>({
    userRoles :[],    
  }),

  actions: {
    //fetch roles so that they are available which creating users
    fetchRoles(){

      return axios.get('api/auth/roles')
        .then(response => {
          if (response && response.data) {
            console.log('Response Data',response.data)
            this.userRoles = response.data
            
            return response.data
          } else {
          //  console.log('error',error)
            throw new Error('Invalid response data')
          }
        })
        .catch(error => {
          console.error(error)
          throw error
        })

    },

    // 👉 Fetch users data
    fetchUsers(params) {
      // console.log(params) // log the incoming params object
      console.log(localStorage.getItem('accessToken'))
      
      return axios.get('api/auth/user-list', { params }) },

    // 👉 Add User
    addUser(userData) {
      return new Promise((resolve, reject) => {
        axios.post('api/auth/register', {
          user: userData,
        }).then(response => {
          console.log(response.data)

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

    // 👉 fetch single user
    fetchUser(id) {
      //alert(id)
      
      return new Promise((resolve, reject) => {
        axios.get(`api/auth/user/show/${id}`)
          .then(response => resolve(response.data))
          .catch(error => reject(error))
      })
    },
  },
})
