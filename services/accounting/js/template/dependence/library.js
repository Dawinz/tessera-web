$(document).ready(function () {
  if (typeof window.srInitAccountsDatepickers === 'function') {
    window.srInitAccountsDatepickers(document);
  } else if (typeof flatpickr !== 'undefined') {
    $('.fc-datepicker').each(function () {
      if (this._flatpickr) return;
      var val = (($(this).val() || '') + '').trim();
      var fmt = /-\d{2}-\d{2}/.test(val) ? 'Y-m-d' : 'm/d/Y';
      flatpickr(this, {
        dateFormat: fmt,
        allowInput: true,
        disableMobile: true,
        onChange: function (selectedDates, dateStr, instance) {
          instance.input.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
  }

  if ($.fn.select2) {
    $('.select2').select2({
      width: 'style',
      searchInputPlaceholder: 'Search'
    });
  }
});
