import { canNavigate } from '@layouts/plugins/casl'
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
      redirect: to => {
        const userData = JSON.parse(localStorage.getItem('userData') || '{}')

        console.log(userData[0])

        //const userRole = userData && userData.role ? userData.role : null
        const userRole = userData[0]  ? userData[0] : null
       
        if (userRole === 'admin')
          return { name: 'user-list' }
        if (userRole === 'user')
          return { name: 'frontend-rooms-list' }
        
        return { name: 'login', query: to.query }
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

  // Check if the route requires authentication
  if (to.meta.requiresAuth) {
    // If the user is not logged in, redirect to the login page with a query param to preserve the original destination
    if (!isLoggedIn) {
      return {
        name: 'login',
        query: { to: to.name !== 'index' ? to.fullPath : undefined },
      }
    }

    // Check if the user has the necessary ability to navigate to the route
    if (!canNavigate(to)) {
      // If the user is logged in but doesn't have the necessary ability, redirect to the not-authorized page
      return { name: 'not-authorized' }
    }
  }

  // If the route doesn't require authentication, allow navigation
  return true
})
export default router

