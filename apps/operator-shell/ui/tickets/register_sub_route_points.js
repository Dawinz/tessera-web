(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-spoint-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/sub_route_points.php',
    saveUrl: '/tickets/api/console/sub_route_point_save.php',
    deleteUrl: '/tickets/api/console/sub_route_point_delete.php',
    addLabel: 'Add sub-route point',
    emptyTitle: 'No sub-route points yet',
    deleteConfirm: 'Delete this sub-route point?',
    columns: [
      { key: 'name', label: 'Place' },
      { key: 'point', label: 'Point' },
      { key: 'route_name', label: 'Sub-route' },
      { key: 'time', label: 'Time' }
    ],
    fields: [
      { name: 'sub_route_id', label: 'Sub-route', type: 'select', required: true, options: 'sub_routes', optionValue: 'id', optionLabel: 'label' },
      { name: 'point', label: 'Point', type: 'select', required: true, options: 'points', optionValue: 'id', optionLabel: 'label' },
      { name: 'name', label: 'Place name', type: 'text', required: true },
      { name: 'a_time', label: 'Time', type: 'time', required: true }
    ]
  });
})();
