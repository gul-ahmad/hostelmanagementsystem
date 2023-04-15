// import { setupLayouts } from 'virtual:generated-layouts'
// import { createRouter, createWebHistory } from 'vue-router'
// import routes from '~pages'

// const router = createRouter({
//   history: createWebHistory(import.meta.env.BASE_URL),
//   routes: [
//     ...setupLayouts(routes),
//   ],
// })

// export default router
import { setupLayouts } from 'virtual:generated-layouts'
import { createRouter, createWebHistory } from 'vue-router'
import routes from '~pages'
import { isUserLoggedIn } from './utils'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // ℹ️ We are redirecting to different pages based on role.
    // NOTE: Role is just for UI purposes. ACL is based on abilities.
    {
      path: '/',

      // redirect: to => {
      //   const userData = JSON.parse(localStorage.getItem('userData') || '{}')
      //   const userRole = userData && userData.role ? userData.role : null
      //   if (userRole === 'admin')
      //     return { name: 'dashboards-analytics' }
      //   if (userRole === 'client')
      //     return { name: 'access-control' }
        
      //   return { name: 'login', query: to.query }
      // },
      redirect: to => {
        const isLoggedIn = isUserLoggedIn()
        if (isLoggedIn) {
          return { name: 'user-list' }
        } else {
          return { name: 'login', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
        }
      },
    },
    {
      path: '/pages/user-profile',
      redirect: () => ({ name: 'pages-user-profile-tab', params: { tab: 'profile' } }),
    },
    {
      path: '/pages/account-settings',
      redirect: () => ({ name: 'pages-account-settings-tab', params: { tab: 'account' } }),
    },
    ...setupLayouts(routes),
  ],
})


// Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
//router.beforeEach(to => {
//   const isLoggedIn = isUserLoggedIn()


//   if (canNavigate(to)) {
//     if (to.meta.redirectIfLoggedIn && isLoggedIn)
//       return '/'
//   }
//   else {
//     if (isLoggedIn)
//       return { name: 'not-authorized' }
//     else
//       return { name: 'login', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
//   }
// })
router.beforeEach(to => {
  const isLoggedIn = isUserLoggedIn()

  if (to.name !== 'Login' && !isLoggedIn) {
    return { name: 'Login', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
  } else {
    return true
  }
})
export default router

