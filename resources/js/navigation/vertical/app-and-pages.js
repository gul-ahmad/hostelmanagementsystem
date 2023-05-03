export default [
  { heading: 'Manage Hostel' },

 
  {
    title: 'Manage',
    icon: { icon: 'tabler-shield' },
    children: [
      { title: 'Users', to: 'user-list' },
      { title: 'Rooms', to: 'room-list' },

      // { title: 'View', to: { name: 'apps-user-view-id', params: { id: 21 } } },
    ],
  },

]
