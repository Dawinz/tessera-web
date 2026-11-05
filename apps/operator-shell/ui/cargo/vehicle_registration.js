(function () {
  if (!window.TesseraCargoSetup) return;
  window.TesseraCargoSetup({
    root: 'tds-cargo-vehicle-root',
    listUrl: '/cargo/api/console/vehicles.php',
    saveUrl: '/cargo/api/console/vehicle_save.php',
    deleteUrl: '/cargo/api/console/vehicle_delete.php',
    edit: true,
    addLabel: 'Add vehicle',
    editLabel: 'Edit vehicle',
    emptyTitle: 'No vehicles yet',
    emptyLead: 'Add the bus name, driver, and conductor used on cargo manifests.',
    deleteConfirm: 'Delete this vehicle?',
    columns: [
      { key: 'name', label: 'Name' },
      { key: 'driver', label: 'Driver' },
      { key: 'conductor', label: 'Conductor' }
    ],
    fields: [
      { name: 'name', label: 'Vehicle name', type: 'text', required: true },
      { name: 'driver', label: 'Driver', type: 'text', required: true },
      { name: 'conductor', label: 'Conductor', type: 'text', required: true }
    ]
  });
})();
