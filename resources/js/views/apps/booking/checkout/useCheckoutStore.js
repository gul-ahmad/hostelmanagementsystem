// src/store/index.js
import { defineStore } from 'pinia'

export const useCheckoutStore = defineStore('checkout', {
  state: () => ({
    checkoutData: null,
  }),
  persist:true,
  actions: {
    setCheckoutData(data) {
      this.checkoutData = data
    },
    clearCheckoutData() {

      this.checkoutData = null
      
    },
  },
})
