/* Cargo module tour packs */
(function () {
  'use strict';
  if (!window.TesseraTour) return;

  window.TesseraTour.register('cargo', function (cfg) {
    var roleLabel = cfg.roleLabel || cfg.role || 'staff';
    return [
      {
        roles: ['*'],
        popover: {
          title: 'Welcome to Cargo',
          description:
            'Walk through parcel registration and checkout for your role (' + roleLabel + ').',
        },
      },
      {
        roles: ['*'],
        element: 'modules',
        popover: {
          title: 'Module switcher',
          description: 'Tickets · Cargo · Accounts — same staff session across modules.',
        },
      },
      {
        roles: ['*'],
        element: 'topnav',
        popover: {
          title: 'Cargo menu',
          description: 'Register luggage, view all parcels, store, checkout, and vehicle reports.',
        },
      },
      {
        roles: ['owner', 'manager', 'cargo_agent'],
        element: 'nav-register',
        popover: {
          title: 'Register parcel',
          description: 'Create a new luggage / cargo record for a customer.',
        },
      },
      {
        roles: ['owner', 'manager', 'cargo_agent'],
        element: 'nav-all',
        popover: {
          title: 'All luggage',
          description: 'Search and manage parcels already in the system.',
        },
      },
      {
        roles: ['owner', 'manager'],
        element: 'nav-vehicle',
        popover: {
          title: 'Vehicle / trip cargo',
          description: 'See what is loaded on a vehicle for a trip date.',
        },
      },
      {
        roles: ['*'],
        element: 'profile',
        popover: {
          title: 'Guidelines control',
          description: 'Turn Guidelines On/Off or restart this tour from your profile menu.',
        },
      },
      {
        roles: ['cargo_agent'],
        popover: {
          title: 'Cargo clerk tip',
          description: 'Typical path: Register → print label → later Checkout / arrived when the parcel moves.',
        },
      },
    ];
  });
})();
