export default [
  { heading: 'Manage Hostel' },
  {
    title: 'Rooms',
    icon: { icon: 'tabler-file' },
    children: [
      { title: 'Rooms List', to: 'room-list' },
      { title: 'View', to: { name: 'room-view-id', params: { id: 22 } } },
    ],
  },
  {
    title: 'Users',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: 'user-list' },
    ],
  },
  {
    title: 'Reservations',
    icon: { icon: 'tabler-user' },
    children: [
      { title: 'List', to: 'reservation-list' },
    ],
  },

  {
    title: 'Rooms',
    icon: { icon: 'tabler-file' },
    to: 'frontend-rooms-list',
  },
  {
    title: 'Rooms test',
    icon: { icon: 'tabler-file' },
    to: 'frontend-rooms-list',
  },
  {
    title: 'Rooms list',
    icon: { icon: 'tabler-file' },
    to: 'frontend-rooms-list',
  },
]
