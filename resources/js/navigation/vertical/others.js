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
  { title: 'View',
    to: { name: 'frontend-user-view-id', params: { id: 'id' } },
    action: 'read',
    subject: 'public',
    meta: { requiresAuth: true } },


  // { 
  //   title: 'Booking',
  //   icon: { icon: 'tabler-shield' },
  //   to: { name: 'booking-checkout' }, 
  
  // },
  

]
