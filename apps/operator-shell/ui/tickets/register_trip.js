(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-trip-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/trip_templates.php',
    saveUrl: '/tickets/api/console/trip_template_save.php',
    deleteUrl: '/tickets/api/console/trip_template_delete.php',
    statusUrl: '/tickets/api/console/trip_template_status.php',
    addLabel: 'Add trip template',
    emptyTitle: 'No trip templates yet',
    deleteConfirm: 'Delete this trip template?',
    columns: [
      { key: 'day', label: 'Weekday' },
      { key: 'fullname', label: 'Route' },
      { key: 'bus', label: 'Bus' },
      { key: 'status', label: 'Status' },
      { key: 'detail', label: 'Detail' }
    ],
    filters: [
      { name: 'day', label: 'Weekday', options: 'weekdays', optionValue: 'name', optionLabel: 'name' },
      { name: 'bus', label: 'Bus', options: 'vehicles', optionValue: 'name', optionLabel: 'name' },
      { name: 'status', label: 'Status', options: [{ id: 'active', label: 'Active' }, { id: 'Not Active', label: 'Not active' }], optionValue: 'id', optionLabel: 'label' }
    ],
    fields: [
      { name: 'day', label: 'Weekday', type: 'select', required: true, options: 'weekdays', optionValue: 'name', optionLabel: 'name' },
      { name: 'route_id', label: 'Main route', type: 'select', required: true, options: 'main_routes', optionValue: 'id', optionLabel: 'fullname' },
      { name: 'bus', label: 'Bus', type: 'select', required: true, options: 'vehicles', optionValue: 'name', optionLabel: 'name' },
      { name: 'detail', label: 'Detail', type: 'text' }
    ]
  });
})();
