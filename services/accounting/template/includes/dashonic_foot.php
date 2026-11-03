<?php
/**
 * Dashonic footer scripts for Accounts pages.
 * Loads after page content. Page-specific JS that needs DataTables should run after this include.
 */
?>
<!-- Dashonic core JS -->
<script src="/tickets/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/tickets/assets/libs/metismenujs/metismenujs.min.js"></script>
<script src="/tickets/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/tickets/assets/libs/feather-icons/feather.min.js"></script>
<script src="/tickets/assets/libs/sweetalert2/sweetalert2.min.js"></script>
<script src="/tickets/assets/libs/flatpickr/flatpickr.min.js"></script>

<!-- Vendor: Select2 + DataTables + Buttons (single load for Accounts) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.2/js/buttons.print.min.js"></script>

<!-- Accounts upload / gallery helpers (safe no-op if unused on the page) -->
<script>
/* Must be set before dropzone.js registers DOMContentLoaded auto-discover. */
window.Dropzone = window.Dropzone || {};
window.Dropzone.autoDiscover = false;
</script>
<script src="/accounts/dropzone/dropzone.js"></script>
<script>
if (window.Dropzone) {
  Dropzone.autoDiscover = false;
}
</script>
<script src="/accounts/lib/lightslider/js/lightslider.min.js"></script>
<script>
window.srSafeDropzone = function (selector, options) {
  if (!window.jQuery || !jQuery.fn.dropzone) return;
  if (window.Dropzone) Dropzone.autoDiscover = false;
  options = options || {};
  if (!options.url) return;
  jQuery(selector).each(function () {
    if (this.dropzone || jQuery(this).data('sr-dropzone')) return;
    jQuery(this).dropzone(options);
    jQuery(this).data('sr-dropzone', true);
  });
};
</script>

<!-- Dashonic app init -->
<script src="/tickets/assets/js/app.js?v=<?php echo @filemtime(dirname(__DIR__, 3) . '/tickets/assets/js/app.js') ?: @filemtime('/var/www/html/tickets/assets/js/app.js') ?: time(); ?>"></script>
<script>
(function () {
  function softenInlineStatusColors(root) {
    var scope = root || document;
    scope.querySelectorAll('td[style], tr[style]').forEach(function (el) {
      var s = (el.getAttribute('style') || '').toLowerCase();
      if (!s) return;
      if (s.indexOf('#03a811') !== -1 || s.indexOf('rgb(3, 168, 17)') !== -1 || s.indexOf('background-color:green') !== -1 || s.indexOf('background:green') !== -1) {
        el.style.backgroundColor = '';
        el.style.color = '';
        var tr = el.closest('tr');
        if (tr) tr.classList.add('sr-row-complete');
      }
      if (s.indexOf('#fad532') !== -1 || s.indexOf('rgb(250, 213, 50)') !== -1) {
        el.style.backgroundColor = '';
        el.style.color = '';
        var tr2 = el.closest('tr');
        if (tr2) tr2.classList.add('sr-row-half');
      }
      if (s.indexOf('#dc3545') !== -1 || s.indexOf('background:#dc3545') !== -1) {
        el.style.backgroundColor = '';
        el.style.color = '';
        el.classList.add('sr-total-cell');
      }
    });
  }

  function initAccountsDatepickers(root) {
    if (typeof flatpickr === 'undefined') return;
    var scope = root || document;
    scope.querySelectorAll('.fc-datepicker').forEach(function (el) {
      if (el._flatpickr) return;
      var val = (el.value || '').trim();
      var fmt = /-\d{2}-\d{2}/.test(val) ? 'Y-m-d' : 'm/d/Y';
      flatpickr(el, {
        dateFormat: fmt,
        allowInput: true,
        disableMobile: true,
        onChange: function () {
          el.dispatchEvent(new Event('change', { bubbles: true }));
        }
      });
    });
  }

  window.srInitAccountsDatepickers = initAccountsDatepickers;

  window.srCleanupOffcanvasBackdrop = function () {
    document.querySelectorAll(
      '#load_vehicle_account > .offcanvas-backdrop, #load_daily_account > .offcanvas-backdrop, #load_report > .offcanvas-backdrop, #load_vehicle_account > .modal-backdrop, #load_daily_account > .modal-backdrop'
    ).forEach(function (el) { el.remove(); });

    var openOc = document.querySelector('.offcanvas.show');
    var openModal = document.querySelector('.modal.show');
    if (!openOc && !openModal) {
      document.querySelectorAll('.offcanvas-backdrop, .modal-backdrop').forEach(function (el) { el.remove(); });
      document.querySelectorAll('.offcanvas.show').forEach(function (el) { el.classList.remove('show'); });
      document.body.classList.remove('modal-open', 'offcanvas-open', 'overflow-hidden');
      document.body.style.overflow = '';
      document.body.style.paddingRight = '';
    }
  };

  window.srMountAjaxHtml = function (hostId, html) {
    var host = document.getElementById(hostId);
    if (!host) return;
    host.innerHTML = html;
    var offcanvas = host.querySelector('#offcanvasTop');
    if (offcanvas) {
      var mount = document.getElementById('srOffcanvasMount') || document.getElementById('srLedgerOffcanvasMount');
      if (!mount) {
        mount = document.createElement('div');
        mount.id = 'srOffcanvasMount';
        document.body.appendChild(mount);
      }
      mount.innerHTML = '';
      offcanvas.classList.remove('show');
      mount.appendChild(offcanvas);
    }
    window.srCleanupOffcanvasBackdrop();
    softenInlineStatusColors(host);
    initAccountsDatepickers(host);
    setTimeout(window.srCleanupOffcanvasBackdrop, 0);
    setTimeout(window.srCleanupOffcanvasBackdrop, 300);
  };

  /** After ledger AJAX: move offcanvas to body, remove stray backdrops, soften row colors. */
  window.srMountLedgerFragment = function (html, root) {
    var host = root ? (root.jquery ? root[0] : root) : document.getElementById('load_vehicle_account');
    if (!host) return;
    window.srMountAjaxHtml(host.id || 'load_vehicle_account', html);
  };

  window.srCloseOpsNavDropdowns = function srCloseOpsNavDropdowns(exceptItem) {
    var nav = document.querySelector('#page-topbar .topnav .navbar-nav');
    if (!nav) return;
    nav.querySelectorAll('.nav-item.dropdown').forEach(function (other) {
      if (exceptItem && other === exceptItem) return;
      other.classList.remove('show', 'sr-nav-open');
      var menu = other.querySelector(':scope > .dropdown-menu');
      if (menu) {
        menu.classList.remove('show');
        menu.style.removeProperty('display');
      }
      var toggle = other.querySelector('[data-bs-toggle="dropdown"], [data-toggle="dropdown"]');
      if (toggle) {
        toggle.classList.remove('show');
        toggle.setAttribute('aria-expanded', 'false');
        if (toggle === document.activeElement) {
          toggle.blur();
        }
        if (window.bootstrap && bootstrap.Dropdown) {
          var inst = bootstrap.Dropdown.getInstance(toggle);
          if (inst) {
            try { inst.hide(); } catch (e) { /* ignore */ }
          }
        }
      }
    });
  }

  window.srTopnavExclusiveDropdowns = function srTopnavExclusiveDropdowns() {
    var topbar = document.getElementById('page-topbar');
    if (!topbar || topbar.dataset.srExclusiveNav === '1') return;
    topbar.dataset.srExclusiveNav = '1';

    topbar.addEventListener('mouseover', function (e) {
      var item = e.target.closest('.topnav .navbar-nav > .nav-item');
      if (!item) return;
      if (item.classList.contains('dropdown')) {
        item.classList.add('sr-nav-open');
        srCloseOpsNavDropdowns(item);
      } else {
        srCloseOpsNavDropdowns(null);
      }
    });

    var topnav = topbar.querySelector('.topnav');
    if (topnav) {
      topnav.addEventListener('mouseleave', function () {
        srCloseOpsNavDropdowns(null);
      });
    }

    topbar.addEventListener('show.bs.dropdown', function (e) {
      var openItem = e.target.closest('.topnav .navbar-nav > .nav-item.dropdown');
      if (!openItem) return;
      openItem.classList.add('sr-nav-open');
      srCloseOpsNavDropdowns(openItem);
    });
  };

  document.addEventListener('DOMContentLoaded', function () {
    document.body.classList.add('sr-platform-body');
    window.srTopnavExclusiveDropdowns();
    window.srCleanupOffcanvasBackdrop();
    setTimeout(window.srCleanupOffcanvasBackdrop, 0);
    setTimeout(window.srCleanupOffcanvasBackdrop, 500);
    document.querySelectorAll('.offcanvas').forEach(function (el) {
      el.addEventListener('hidden.bs.offcanvas', window.srCleanupOffcanvasBackdrop);
    });
    softenInlineStatusColors(document);
    initAccountsDatepickers(document);
    if (window.jQuery) {
      jQuery(document).on('draw.dt', function (e) {
        softenInlineStatusColors(e.target);
      });
    }
  });
})();
</script>
