// app-and-pages.js
export default [
  { heading: 'Manage Hostel' },
  {
    title: 'Rooms',
    icon: { icon: 'tabler-file' },
    action: 'manage',
    subject: 'all',
    children: [
      { title: 'Rooms List', to: 'room-list', meta: { requiresAuth: true } },
      { title: 'View', to: { name: 'room-view-id', params: { id: 22 } }, meta: { requiresAuth: true } },
    ],
    meta: {
      requiresAuth: true,
    },
  },
  {
    title: 'Users',
    icon: { icon: 'tabler-user' },
    to: 'user-list',
    action: 'manage',
    subject: 'all',

    // children: [
    //   { title: 'List', to: 'user-list', meta: { requiresAuth: true } },
    // ],
    meta: {
      requiresAuth: true,
    },
  },
  {
    title: 'Reservations',
    icon: { icon: 'tabler-user' },
    action: 'manage',
    subject: 'all',
    children: [
      { title: 'List', to: 'reservation-list', meta: { requiresAuth: true } },
    ],
    meta: {
      requiresAuth: true,
    },
  },
  {
    title: 'Wizard Examples',
    icon: { icon: 'tabler-forms' },
    action: 'reamanaged',
    subject: 'all',
    children: [
      { title: 'Checkout', to: { name: 'wizard-examples-checkout' } },
      { title: 'Property Listing', to: { name: 'wizard-examples-property-listing' } },
      { title: 'Create Deal', to: { name: 'wizard-examples-create-deal' } },
    ],
  },

  // Top-level menu item without children doesn't require authentication
  // Therefore, it should always be shown
  // {
  //   title: 'Frontend Rooms List',
  //   icon: { icon: 'tabler-file' },
  //   to: 'frontend-rooms-list',
  //   meta: {
  //     requiresAuth: false,
  //   },
  // },
]
