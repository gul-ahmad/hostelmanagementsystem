export default [
  { heading: 'Manage Hostel' },
  {
    title: 'Rooms',
    icon: { icon: 'tabler-file' },
    children: [
      { title: 'Rooms List', to: 'room-list', permission: 'list_users', showForGuest: false },
      { title: 'View', to: { name: 'room-view-id', params: { id: 22 } }, permission: 'view_rooms', showForGuest: false },
    ],
  },
  {
    title: 'Users',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: 'user-list', permission: 'list_users', showForGuest: false },
    ],
  },
  {
    title: 'Reservations',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: 'reservation-list', permission: 'view_bookings', showForGuest: false },
    ],
  },

  {
    title: 'Rooms',
    icon: { icon: 'tabler-file' },
    to: 'rooms-available',
    showForGuest: true, // Show for non-logged-in users
  },
]
