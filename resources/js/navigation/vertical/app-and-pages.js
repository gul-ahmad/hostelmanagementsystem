export default [
  { heading: 'Manage Hostel' },

 
  // {
  //   title: 'Manage',
  //   icon: { icon: 'tabler-shield' },
  //   children: [
  //     { title: 'Users', to: 'user-list' },
  //     { title: 'Rooms', to: 'room-list' },

    
  //   ],
  // },

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

]
