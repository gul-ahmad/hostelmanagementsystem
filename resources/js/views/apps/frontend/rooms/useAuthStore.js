import { defineStore } from "pinia"


export const  useAuthStore = defineStore('authStore',{

  state: () => ({

    isAuthenticated : false,
  }),

  actions: {

    setAuthentication(isAuthenticated) {
      this.isAuthenticated =isAuthenticated    
    },

  },

})
