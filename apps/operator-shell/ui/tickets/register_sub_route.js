(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-subroute-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/sub_routes.php',
    saveUrl: '/tickets/api/console/sub_route_save.php',
    deleteUrl: '/tickets/api/console/sub_route_delete.php',
    addLabel: 'Add sub-route',
    emptyTitle: 'No sub-routes yet',
    deleteConfirm: 'Delete this sub-route?',
    columns: [
      { key: 'name', label: 'Name' },
      { key: 'start_route', label: 'Start' },
      { key: 'end_route', label: 'End' },
      { key: 'route_name', label: 'Main route' },
      { key: 'fare_price', label: 'Fare', format: 'money' },
      { key: 'd_time', label: 'Departure' },
      { key: 'a_time', label: 'Arrival' },
      { key: 'active_day', label: 'Active after' }
    ],
    fields: [
      { name: 'main_route_id', label: 'Main route', type: 'select', required: true, options: 'main_routes', optionValue: 'id', optionLabel: 'fullname', placeholder: 'Main route' },
      { name: 'start_route', label: 'Starting point', type: 'select', required: true, options: 'locations', optionValue: 'name', optionLabel: 'name' },
      { name: 'end_route', label: 'Ending point', type: 'select', required: true, options: 'locations', optionValue: 'name', optionLabel: 'name' },
      { name: 'currency', label: 'Currency', type: 'select', required: true, options: 'currencies', optionValue: 'country', optionLabel: 'country' },
      { name: 'fare', label: 'Fare', type: 'number', required: true },
      { name: 'name', label: 'Name', type: 'text', required: true },
      { name: 'active_day', label: 'Active after (days)', type: 'number' },
      { name: 'd_time', label: 'Departure', type: 'time' },
      { name: 'a_time', label: 'Arrival', type: 'time' },
      { name: 'detail', label: 'Detail', type: 'textarea' }
    ]
  });
})();
