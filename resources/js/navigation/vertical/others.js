export default [
  { heading: 'Others' },
  {
    title: 'Access Control',
    icon: { icon: 'tabler-shield' },
    to: 'frontend-rooms-list',
    action: 'read',
    subject: 'view_bookings',
  },
  {
    title: 'Booking',
    icon: { icon: 'tabler-shield' },
    to: { name: 'booking-checkout' }, 
    action: 'read',
    subject: 'view_bookings',
  },
  

]
