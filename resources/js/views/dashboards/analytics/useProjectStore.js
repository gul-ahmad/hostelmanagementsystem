import { defineStore } from 'pinia'
import axios from '@axios'

export const useProjectStore = defineStore('ProjectStore', {
  actions: {
    // 👉 Fetch all project
    fetchProjects(params) {
      return axios.get('/dashboard/analytics/projects', { params })
    },
  },
})
