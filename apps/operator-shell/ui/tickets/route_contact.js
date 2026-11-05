(function () {
  if (!window.TesseraNetworkScreen) return;
  window.TesseraNetworkScreen({
    root: 'tds-contact-root',
    bootstrapUrl: '/tickets/api/console/network_bootstrap.php',
    listUrl: '/tickets/api/console/route_contacts.php',
    saveUrl: '/tickets/api/console/route_contact_save.php',
    deleteUrl: '/tickets/api/console/route_contact_delete.php',
    addLabel: 'Add contact',
    emptyTitle: 'No route contacts yet',
    emptyLead: 'Add a name and phone number for a route.',
    deleteConfirm: 'Delete this contact?',
    filters: [
      { name: 'route_pk', label: 'Route', options: 'routes', optionValue: 'id', optionLabel: 'label' }
    ],
    columns: [
      { key: 'route_name', label: 'Route' },
      { key: 'name', label: 'Name' },
      { key: 'contact', label: 'Phone' }
    ],
    fields: [
      { name: 'route_pk', label: 'Route', type: 'select', required: true, options: 'routes', optionValue: 'id', optionLabel: 'label', placeholder: 'Route' },
      { name: 'name', label: 'Name', type: 'text', required: true },
      { name: 'contact', label: 'Phone', type: 'text', required: true }
    ]
  });
})();
