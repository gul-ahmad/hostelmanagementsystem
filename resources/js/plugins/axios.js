import axios from 'axios'

const axiosIns = axios.create({
// You can add your headers here
// ================================
  baseURL: 'http://127.0.0.1:8000/',

// timeout: 1000,
// headers: {'X-Custom-Header': 'foobar'}
})


// Add a request interceptor
axios.interceptors.request.use(
  function (config) {
    // Add Authorization header to the request
    const token = JSON.parse(localStorage.getItem('accessToken'))
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    // Add content-type header to the request
    config.headers['Content-Type'] = 'application/json'
    
    return config
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error)
  },
)

export default axiosIns
