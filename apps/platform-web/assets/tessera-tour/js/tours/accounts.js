/* Accounts module tour packs */
(function () {
  'use strict';
  if (!window.TesseraTour) return;

  window.TesseraTour.register('accounts', function (cfg) {
    var roleLabel = cfg.roleLabel || cfg.role || 'staff';
    return [
      {
        roles: ['*'],
        popover: {
          title: 'Welcome to Accounts',
          description:
            'Highlights for accounting work (' + roleLabel + '). Follow the steps to find manifests and expenses.',
        },
      },
      {
        roles: ['*'],
        element: 'modules',
        popover: {
          title: 'Modules',
          description: 'Switch back to Tickets or Cargo when you need operational context.',
        },
      },
      {
        roles: ['*'],
        element: 'topnav',
        popover: {
          title: 'Accounts menu',
          description: 'Manifests, vehicle accounts, job cards, office expenses, and reports.',
        },
      },
      {
        roles: ['owner', 'manager', 'accountant'],
        element: 'nav-manifest',
        popover: {
          title: 'Manifest',
          description: 'Reconcile trip income and linked expense lines.',
        },
      },
      {
        roles: ['owner', 'manager', 'accountant'],
        element: 'nav-expenses',
        popover: {
          title: 'Expenses',
          description: 'Record office or vehicle expenses against the right categories.',
        },
      },
      {
        roles: ['*'],
        element: 'profile',
        popover: {
          title: 'Guidelines',
          description: 'Keep Guidelines On while training new accountants; turn Off when you know the paths.',
        },
      },
      {
        roles: ['accountant'],
        popover: {
          title: 'Accountant tip',
          description: 'Start from Manifest for a trip date, then post expenses / withdrawals as your process requires.',
        },
      },
    ];
  });
})();
