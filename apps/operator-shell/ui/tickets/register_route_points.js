(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-rpoint-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/route_points.php',
    saveUrl: '/tickets/api/console/route_point_save.php',
    deleteUrl: '/tickets/api/console/route_point_delete.php',
    addLabel: 'Add route point',
    emptyTitle: 'No route points yet',
    deleteConfirm: 'Delete this route point?',
    columns: [
      { key: 'name', label: 'Place' },
      { key: 'point', label: 'Point' },
      { key: 'route_name', label: 'Route' },
      { key: 'start_location', label: 'Start' },
      { key: 'end_location', label: 'End' }
    ],
    fields: [
      { name: 'route_pk', label: 'Route', type: 'select', required: true, options: 'routes', optionValue: 'id', optionLabel: 'label' },
      { name: 'point', label: 'Point', type: 'select', required: true, options: 'points', optionValue: 'id', optionLabel: 'label' },
      { name: 'name', label: 'Place name', type: 'text', required: true }
    ]
  });
})();
