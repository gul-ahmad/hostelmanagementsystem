export default [
  {
    title: 'Dashboards',
    icon: { icon: 'tabler-smart-home' },
    children: [
      {
        title: 'Users',
        to: 'dashboards-user-list',
      },
      {
        title: 'eCommerce',
        to: 'dashboards-ecommerce',
      },
      {
        title: 'CRM',
        to: 'dashboards-crm',
      },
    ],
    badgeContent: '2',
    badgeClass: 'bg-light-primary text-primary',
  },
]
