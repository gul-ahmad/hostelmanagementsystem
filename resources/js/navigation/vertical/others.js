export default [
  { heading: 'Others' },
  {
    title: 'Rooms',
    icon: { icon: 'tabler-shield' },
    to: 'frontend-rooms-list',
    action: 'read',
    subject: 'public',
    meta: {
      requiresAuth: false, // Change this to false
    },
 
  },

  // { 
  //   title: 'Booking',
  //   icon: { icon: 'tabler-shield' },
  //   to: { name: 'booking-checkout' }, 
  
  // },
  

]
