<?php
/**
 * Dashonic <head> assets for Accounts pages.
 * Uses /tickets/assets/ (same Dashonic CSS/JS as the Tickets module).
 * Page-specific CSS can be added after this include.
 */
$__page_label = $__page_title ?? 'Accounts';
if (!function_exists('tessera_document_title')) {
	require_once __DIR__ . '/tessera_tenant.php';
}
$__tessera_mod = isset($tessera_page_module) && (string) $tessera_page_module !== ''
	? (string) $tessera_page_module
	: 'Accounts';
$__page_title_full = tessera_document_title((string) $__page_label, $__tessera_mod);
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Tessera" name="author">
<title><?php echo htmlspecialchars($__page_title_full, ENT_QUOTES, 'UTF-8'); ?></title>
<?php echo tessera_favicon_link_tags('/tickets/assets/images'); ?>

<!-- Dashonic core CSS (same as Tickets) -->
<link href="/tickets/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="/tickets/assets/css/icons.min.css" rel="stylesheet" type="text/css">
<link href="/tickets/assets/css/app.min.css" rel="stylesheet" type="text/css">
<link href="/tickets/assets/css/sr-platform-shell.css?v=<?php echo @filemtime(dirname(__DIR__, 2) . '/../../services/tickets/assets/css/sr-platform-shell.css') ?: time(); ?>" rel="stylesheet">
<link href="/accounts/css/tessera-accounts-layout.css?v=<?php echo @filemtime(dirname(__DIR__, 2) . '/css/tessera-accounts-layout.css') ?: time(); ?>" rel="stylesheet">
<?php
$tessera_states_module = 'accounts';
include __DIR__ . '/tessera_states_boot.php';
?>

<!-- Vendor libs used by Accounts pages -->
<link href="/tickets/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet">
<link href="/tickets/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/3.1.2/css/buttons.dataTables.min.css" rel="stylesheet">
<link href="/accounts/dropzone/dropzone.css" rel="stylesheet">
<link href="/accounts/lib/lightslider/css/lightslider.min.css" rel="stylesheet">

<!-- jQuery + early vendors (page scripts often call select2/datepicker/effect before the footer) -->
<script src="/tickets/assets/libs/jquery/jquery.min.js"></script>
<script src="/accounts/lib/jquery/jquery_ui.js"></script>
<script src="/tickets/assets/libs/flatpickr/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- DataTables: many Accounts pages init tables in body scripts before dashonic_foot -->
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>
<script>
/* Prefer jQuery UI datepicker; fall back to Flatpickr for legacy $('.datepicker') / $('.fc-datepicker') calls. */
(function ($) {
  if (!$ || !$.fn) return;
  if (typeof $.fn.effect !== 'function') {
    $.fn.effect = function () { return this; };
  }
  var uiDatepicker = $.fn.datepicker;
  $.fn.datepicker = function (options) {
    if (typeof uiDatepicker === 'function') {
      try { return uiDatepicker.apply(this, arguments); } catch (e) { /* fall through */ }
    }
    if (typeof flatpickr === 'undefined') return this;
    var opts = options || {};
    var fmt = 'Y-m-d';
    if (opts.dateFormat === 'mm/dd/yy' || opts.dateFormat === 'm/d/Y') fmt = 'm/d/Y';
    else if (typeof opts.dateFormat === 'string' && opts.dateFormat.indexOf('Y') !== -1) fmt = opts.dateFormat;
    this.each(function () {
      var el = this;
      if (el._flatpickr) return;
      flatpickr(el, {
        dateFormat: fmt,
        allowInput: true,
        disableMobile: true,
        onChange: function () {
          el.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
    return this;
  };
})(window.jQuery);
</script>
