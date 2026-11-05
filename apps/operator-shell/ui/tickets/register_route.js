(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-route-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/routes.php',
    saveUrl: '/tickets/api/console/route_save.php',
    deleteUrl: '/tickets/api/console/route_delete.php',
    edit: true,
    addLabel: 'Add route',
    editLabel: 'Edit route',
    emptyTitle: 'No routes yet',
    deleteConfirm: 'Delete this route? Routes with bookings cannot be deleted.',
    columns: [
      { key: 'fullname', label: 'Route' },
      { key: 'route_name', label: 'Name' },
      { key: 'route_type', label: 'Type', format: 'badge' },
      { key: 'fare_price', label: 'Fare', format: 'money' },
      { key: 'currency', label: 'Currency' },
      { key: 'active_date', label: 'Active days' }
    ],
    fields: [
      { name: 'start_route', label: 'Starting point', type: 'select', required: true, options: 'locations', optionValue: 'name', optionLabel: 'name', placeholder: 'Starting point' },
      { name: 'end_route', label: 'Ending point', type: 'select', required: true, options: 'locations', optionValue: 'name', optionLabel: 'name', placeholder: 'Ending point' },
      { name: 'currency', label: 'Currency', type: 'select', required: true, options: 'currencies', optionValue: 'country', optionLabel: 'country', placeholder: 'Currency' },
      { name: 'fare', label: 'Fare', type: 'number', required: true },
      { name: 'route_name', label: 'Route name', type: 'text', required: true },
      { name: 'active_date', label: 'Active days', type: 'number' },
      { name: 'detail', label: 'Detail', type: 'textarea' },
      { name: 'contact_no1', label: 'Contact 1', type: 'text' },
      { name: 'contact_no2', label: 'Contact 2', type: 'text' }
    ]
  });
})();
