/* Tickets module tour packs — roles: owner, manager, tickets_agent */
(function () {
  'use strict';
  if (!window.TesseraTour) return;

  window.TesseraTour.register('tickets', function (cfg) {
    var roleLabel = cfg.roleLabel || cfg.role || 'staff';
    return [
      {
        roles: ['*'],
        popover: {
          title: 'Welcome to Tickets',
          description:
            'This guided tour highlights the main controls for your role (' +
            roleLabel +
            '). Use Next to continue, or Esc to exit.',
        },
      },
      {
        roles: ['*'],
        element: 'modules',
        side: 'bottom',
        popover: {
          title: 'Switch modules',
          description: 'Jump between Tickets, Cargo, and Accounts without signing in again.',
        },
      },
      {
        roles: ['*'],
        element: 'topnav',
        side: 'bottom',
        popover: {
          title: 'Tickets menu',
          description: 'Open booking, routes, manifests, sales, and the screens your role can access.',
        },
      },
      {
        roles: ['owner', 'manager'],
        element: 'nav-home',
        side: 'bottom',
        popover: {
          title: 'Home & statistics',
          description: 'Charts and daily sales overview for supervisors.',
        },
      },
      {
        roles: ['owner', 'manager', 'tickets_agent'],
        element: 'nav-routes',
        side: 'bottom',
        popover: {
          title: 'Booking & routes',
          description: 'Start here to pick a trip date, choose seats, and issue tickets.',
        },
      },
      {
        roles: ['owner', 'manager'],
        element: 'nav-manifest',
        side: 'bottom',
        popover: {
          title: 'Manifest',
          description: 'Passenger lists per trip — useful before departure.',
        },
      },
      {
        roles: ['*'],
        element: 'profile',
        side: 'left',
        popover: {
          title: 'Your profile (operator menu)',
          description:
            'Open your name here for Operator settings, Guidelines On/Off, and restart tour. Operator settings is never one of the Tickets / Cargo / Accounts pills.',
        },
      },
      {
        roles: ['owner', 'manager'],
        element: 'settings-link',
        side: 'left',
        revealDropdown: true,
        popover: {
          title: 'Operator settings',
          description:
            'Click Operator settings in this menu — it opens /settings/ for payouts, notifications, staff, API, and company profile. The blue-bar Settings tab is different (currency / sign-up / menu roles).',
        },
      },
      {
        roles: ['tickets_agent'],
        popover: {
          title: 'Agent tip',
          description:
            'Your day path: open Booking & routes → select trip → choose seats → collect payment → print. Ask a supervisor for manifests or settings.',
        },
      },
      {
        roles: ['owner'],
        popover: {
          title: 'Super Admin tip',
          description:
            'You can manage all Tickets screens plus Settings (staff roles, payouts, API). Use Cargo and Accounts modules when those teams need support.',
        },
      },
      {
        roles: ['manager'],
        popover: {
          title: 'Admin tip',
          description:
            'You supervise booking ops and most reports. Settings and sensitive payouts may be limited compared to Super Admin.',
        },
      },
    ];
  });
})();
