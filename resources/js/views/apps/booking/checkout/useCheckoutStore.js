// src/store/index.js
import { defineStore } from 'pinia'

export const useCheckoutStore = defineStore('checkout', {
  state: () => ({
    checkoutData: null,
  }),
  actions: {
    setCheckoutData(data) {
      this.checkoutData = data
    },
  },
})
