
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
        const isLoggedIn = isUserLoggedIn()

        if (isLoggedIn) {
          const userAbilities = JSON.parse(localStorage.getItem('userAbilities') || '[]')

          // Check if user has the "list_users" ability
          if (userAbilities.includes('list_users')) {
            return { name: 'user-list' }
          }

          // Check if user has the "view_bookings" ability
          if (userAbilities.includes('view_bookings')) {
            return { name: 'reservation-list' }
          }
        }

        return { name: 'frontend-rooms-list' }
      },
    },
    {
      path: '/pages/user-profile',
      redirect: () => ({ name: 'pages-user-profile-tab', params: { tab: 'profile' } }),
    },
    {
      path: '/pages/room-list',
      redirect: () => ({ name: 'pages-room-list' }),
    },
    {
      path: '/frontend/pages/rooms',
      redirect: () => ({ name: 'frontend-room-list' }),
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

  // Check if the route doesn't require authentication
  // if (!to.meta.requiresAuth) {
  //   // Allow navigation to the route
  //   return true
  // }

  if (to.name !== 'login' && !isLoggedIn) {
    return { name: 'login', query: { to: to.name !== 'index' ? to.fullPath : undefined } }
  } else {
    return true
  }
})
export default router

