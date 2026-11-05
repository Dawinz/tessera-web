(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-loc-root',
    listUrl: '/tickets/api/console/locations.php',
    saveUrl: '/tickets/api/console/location_save.php',
    deleteUrl: '/tickets/api/console/location_delete.php',
    addLabel: 'Add location',
    emptyTitle: 'No locations yet',
    deleteConfirm: 'Delete this location?',
    columns: [{ key: 'name', label: 'Name' }],
    fields: [{ name: 'name', label: 'Location name', type: 'text', required: true }]
  });
})();
