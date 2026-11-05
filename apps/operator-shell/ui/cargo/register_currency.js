(function () {
  if (!window.TesseraCargoSetup) return;
  window.TesseraCargoSetup({
    root: 'tds-cargo-currency-root',
    bootstrapUrl: '/cargo/api/console/currency_bootstrap.php',
    listUrl: '/cargo/api/console/currencies.php',
    saveUrl: '/cargo/api/console/currency_save.php',
    deleteUrl: '/cargo/api/console/currency_delete.php',
    edit: true,
    addLabel: 'Add currency',
    editLabel: 'Edit currency',
    emptyTitle: 'No currencies yet',
    emptyLead: 'Add a currency and its exchange rate.',
    deleteConfirm: 'Delete this currency?',
    columns: [
      { key: 'name', label: 'Currency' },
      { key: 'country_label', label: 'Country' },
      { key: 'initial', label: 'Initial' },
      { key: 'status', label: 'Status', format: 'badge' },
      { key: 'rate', label: 'Rate' }
    ],
    fields: [
      { name: 'name', label: 'Currency name', type: 'text', required: true },
      { name: 'country', label: 'Country', type: 'select', required: true, options: 'countries', optionValue: 'name', optionLabel: 'name', placeholder: 'Country' },
      { name: 'initial', label: 'Initial', type: 'text', required: true },
      { name: 'rate', label: 'Exchange rate', type: 'number', required: true }
    ]
  });
})();
