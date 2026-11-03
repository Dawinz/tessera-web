/* Tessera Control (platform owner) tour */
(function () {
  'use strict';
  if (!window.TesseraTour) return;

  window.TesseraTour.register('control', function () {
    return [
      {
        roles: ['*'],
        popover: {
          title: 'Welcome to Tessera Control',
          description:
            'This is the platform-owner console. The tour points at the sidebar areas you use to onboard and run operators.',
        },
      },
      {
        roles: ['*'],
        element: 'control-brand',
        popover: {
          title: 'Control home',
          description: 'Tessera Control is isolated from operator modules (separate session cookie).',
        },
      },
      {
        roles: ['*'],
        element: 'control-sidebar',
        side: 'right',
        popover: {
          title: 'Platform navigation',
          description: 'Overview, Health, Billing, Support, Operators, and Onboarding live here.',
        },
      },
      {
        roles: ['*'],
        element: 'control-overview',
        side: 'right',
        popover: {
          title: 'Overview',
          description: 'Tenant volume and platform pulse at a glance.',
        },
      },
      {
        roles: ['*'],
        element: 'control-operators',
        side: 'right',
        popover: {
          title: 'Operators',
          description: 'Open a tenant to suspend, view metrics, vault keys, or white-label settings.',
        },
      },
      {
        roles: ['*'],
        element: 'control-onboarding',
        side: 'right',
        popover: {
          title: 'Onboarding cases',
          description: 'Track signup / enterprise cases through provisioning and hypercare.',
        },
      },
      {
        roles: ['*'],
        element: 'control-health',
        side: 'right',
        popover: {
          title: 'Health',
          description: 'Database, backups, dunning, and cron status for ops.',
        },
      },
      {
        roles: ['*'],
        element: 'profile',
        popover: {
          title: 'Guidelines',
          description: 'Turn Guidelines On/Off or restart this tour from the profile menu anytime.',
        },
      },
    ];
  });
})();
