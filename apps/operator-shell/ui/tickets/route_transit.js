(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-transit-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/route_transits.php',
    saveUrl: '/tickets/api/console/route_transit_save.php',
    deleteUrl: '/tickets/api/console/route_transit_delete.php',
    addLabel: 'Add transit',
    emptyTitle: 'No transits yet',
    emptyLead: 'Link a route to the route it continues onto.',
    deleteConfirm: 'Delete this transit?',
    filters: [
      { name: 'route_pk', label: 'Route', options: 'routes', optionValue: 'id', optionLabel: 'label' }
    ],
    columns: [
      { key: 'route_name', label: 'Route' },
      { key: 'route_prev', label: 'Continues onto' }
    ],
    fields: [
      { name: 'route_pk', label: 'Route', type: 'select', required: true, options: 'routes', optionValue: 'id', optionLabel: 'label', placeholder: 'Route' },
      { name: 'transit_route_pk', label: 'Continues onto', type: 'select', required: true, options: 'routes', optionValue: 'id', optionLabel: 'label', placeholder: 'Other route' }
    ]
  });
})();
