/* eslint-disable import/order */
import '@/@iconify/icons-bundle'
import App from '@/App.vue'
import ability from '@/plugins/casl/ability'
import i18n from '@/plugins/i18n'
import layoutsPlugin from '@/plugins/layouts'
import vuetify from '@/plugins/vuetify'
import { loadFonts } from '@/plugins/webfontloader'
import router from '@/router'
import { abilitiesPlugin } from '@casl/vue'

import '@core-scss/template/index.scss'
import '@styles/styles.scss'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'


import { createApp } from 'vue'

loadFonts()

// Create pinia instance
const pinia = createPinia()

// Use plugins
pinia.use(piniaPluginPersistedstate) // Use the plugin here


// Create vue app
const app = createApp(App)


// Use plugins
app.use(vuetify)

//app.use(createPinia())
app.use(pinia)
app.use(router)
app.use(layoutsPlugin)
app.use(i18n)
app.use(abilitiesPlugin, ability, {
  useGlobalProperties: true,
})

// Use the persisted state plugin
//app.use(createPersistedState())

// Mount vue app
app.mount('#app')
