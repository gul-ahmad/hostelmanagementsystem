import { defineStore } from "pinia"


export const  useAuthStore = defineStore('authStore',{

  state: () => ({
    id:null,
    user: null, 
    isAuthenticated : false,
  }),

  persist:true,

  //   persist: {
  //     storage: sessionStorage, // data in sessionStorage is cleared when the page session ends.
  //   },


  actions: {

    setAuthentication(userDetails) {
      this.isAuthenticated = true,
      this.user = userDetails

      this.id = userDetails.id    
    },

  },

})
